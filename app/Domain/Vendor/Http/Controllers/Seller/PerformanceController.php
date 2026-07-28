<?php

namespace App\Domain\Vendor\Http\Controllers\Seller;

use App\Domain\Vendor\Enums\PerformancePeriod;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Services\SellerPerformanceService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function __construct(
        private readonly SellerPerformanceService $service,
    ) {}

    public function dashboard(Request $request)
    {
        $seller = Seller::find(get_seller_id());
        $period = $this->resolvePeriod($request);

        $score = $this->service->score($seller, $period);
        $scores = collect(PerformancePeriod::cases())
            ->mapWithKeys(fn (PerformancePeriod $p) => [
                $p->value => $this->service->score($seller, $p),
            ]);

        $trend = $this->service->trend($seller, 30);

        $alerts = $this->alerts($score);

        return view('seller.performance.dashboard', compact(
            'seller', 'score', 'scores', 'trend', 'alerts', 'period'
        ));
    }

    public function recompute(Request $request)
    {
        $seller = Seller::find(get_seller_id());
        $this->service->recompute($seller);

        return back()->with('success', 'Performance scores refreshed.');
    }

    public function history(Request $request)
    {
        $seller = Seller::find(get_seller_id());
        $trend = $this->service->trend($seller, (int) $request->integer('days', 90));

        return view('seller.performance.history', compact('seller', 'trend'));
    }

    protected function resolvePeriod(Request $request): PerformancePeriod
    {
        $raw = (string) $request->input('period', PerformancePeriod::LAST_30_DAYS->value);
        $period = PerformancePeriod::tryFrom($raw);

        return $period ?? PerformancePeriod::LAST_30_DAYS;
    }

    protected function alerts($score): array
    {
        $alerts = [];

        if ($score->total_orders < (int) config('marketplace.performance.min_orders_for_scoring', 5)) {
            $alerts[] = [
                'level' => 'info',
                'title' => 'Not enough data yet',
                'body' => 'You need at least 5 orders in this period before scoring kicks in.',
            ];
        }

        if ($score->late_shipping_rate >= 0.30) {
            $alerts[] = [
                'level' => 'danger',
                'title' => 'High late-shipping rate',
                'body' => round($score->late_shipping_rate * 100, 1).'% of your shipped orders exceeded the SLA window.',
            ];
        }

        if ($score->cancellation_rate >= 0.10) {
            $alerts[] = [
                'level' => 'warning',
                'title' => 'Cancellation rate is elevated',
                'body' => round($score->cancellation_rate * 100, 1).'% of your orders are being cancelled. Check inventory and lead times.',
            ];
        }

        if ($score->dispute_rate >= 0.10 && $score->returned_orders > 0) {
            $alerts[] = [
                'level' => 'warning',
                'title' => 'Return disputes are climbing',
                'body' => round($score->dispute_rate * 100, 1).'% of returns turned into customer disputes.',
            ];
        }

        if ((float) $score->avg_review_rating > 0 && $score->avg_review_rating < 3.5) {
            $alerts[] = [
                'level' => 'danger',
                'title' => 'Customer rating is low',
                'body' => sprintf('Average rating is %.2f / 5. Review recent complaints.', $score->avg_review_rating),
            ];
        }

        if ($score->chat_count > 0 && $score->response_rate < 0.60) {
            $alerts[] = [
                'level' => 'warning',
                'title' => 'Slow chat response time',
                'body' => round($score->response_rate * 100, 1).'% of customer chats received a response.',
            ];
        }

        return $alerts;
    }
}
