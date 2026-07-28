<?php

namespace App\Domain\Vendor\Http\Controllers\Admin;

use App\Domain\Vendor\Enums\PerformancePeriod;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerPerformanceScore;
use App\Domain\Vendor\Services\SellerPerformanceService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerPerformanceController extends Controller
{
    public function __construct(
        private readonly SellerPerformanceService $service,
    ) {}

    public function index(Request $request)
    {
        $period = $this->resolvePeriod($request);
        $query = SellerPerformanceScore::query()
            ->with('seller:id,business_name,username,code,image,rating,rating_count,status')
            ->where('period', $period->value);

        if ($request->filled('tier')) {
            $query->where('tier', $request->tier);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('seller', fn ($q) => $q
                ->where('business_name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
            );
        }

        $scores = $query->orderByDesc('overall_score')->paginate(20)->withQueryString();

        $stats = SellerPerformanceScore::query()
            ->where('period', $period->value)
            ->selectRaw('tier, COUNT(*) as count, AVG(overall_score) as avg_score')
            ->groupBy('tier')
            ->pluck('count', 'tier');

        $leaderboard = $this->service->leaderboard($period, 10);

        return view('admin.sellers.performance.index', compact(
            'scores', 'stats', 'period', 'leaderboard'
        ));
    }

    public function show(Seller $seller, Request $request)
    {
        $period = $this->resolvePeriod($request);
        $score = $this->service->score($seller, $period);
        $scores = collect(PerformancePeriod::cases())
            ->mapWithKeys(fn (PerformancePeriod $p) => [
                $p->value => $this->service->score($seller, $p),
            ]);
        $trend = $this->service->trend($seller, 60);

        return view('admin.sellers.performance.show', compact('seller', 'score', 'scores', 'trend', 'period'));
    }

    public function recompute(Seller $seller)
    {
        $this->service->recompute($seller);

        return back()->with('success', "Seller #{$seller->id} performance refreshed.");
    }

    protected function resolvePeriod(Request $request): PerformancePeriod
    {
        $raw = (string) $request->input('period', PerformancePeriod::LAST_30_DAYS->value);
        $period = PerformancePeriod::tryFrom($raw);

        return $period ?? PerformancePeriod::LAST_30_DAYS;
    }
}
