<?php

namespace App\Domain\Vendor\Services;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderStatusLog;
use App\Domain\Order\Models\ReturnRequest;
use App\Domain\Review\Models\Review;
use App\Domain\Vendor\Enums\PerformancePeriod;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerChat;
use Illuminate\Support\Carbon;

/**
 * Reads raw business-data signals for one seller over one period.
 * Output: counts & rates only — no scoring math.
 */
class PerformanceMetricsService
{
    public function metricsFor(Seller $seller, PerformancePeriod $period, ?Carbon $now = null): array
    {
        $now ??= now();
        $start = $period->start($now);
        $end = $now->copy()->endOfDay();

        $orderQuery = Order::query()->where('seller_id', $seller->id);
        if ($start) {
            $orderQuery->whereBetween('created_at', [$start, $end]);
        }

        // 1) Cancellation rate
        $counts = $this->orderCounts($seller->id, $start, $end);

        // 2) Late-shipping rate (we need time between ACCEPTED → SHIPPED vs SLA)
        $shipping = $this->shippingMetrics($seller->id, $start, $end);

        // 3) Customer rating
        $rating = $this->reviewMetrics($seller->id, $start, $end);

        // 4) Response rate & average response hours
        $response = $this->responseMetrics($seller->id, $start, $end);

        // 5) Returns/dispute rate
        $dispute = $this->disputeMetrics($seller->id, $start, $end);

        return [
            'period' => $period->value,
            'period_start' => $start?->toDateTimeString(),
            'period_end' => $end->toDateTimeString(),

            'total_orders' => $counts['total_orders'],
            'cancelled_orders' => $counts['cancelled_orders'],
            'shipped_orders' => $shipping['shipped_orders'],
            'late_shipped_orders' => $shipping['late_shipped_orders'],
            'delivered_orders' => $counts['delivered_orders'],
            'refunded_orders' => $counts['refunded_orders'],
            'returned_orders' => $counts['returned_orders'],
            'disputed_returns' => $dispute['disputed_returns'],

            'avg_shipping_hours' => $shipping['avg_shipping_hours'],
            'avg_response_hours' => $response['avg_response_hours'],

            'cancellation_rate' => $this->rate($counts['cancelled_orders'], $counts['total_orders']),
            'late_shipping_rate' => $this->rate($shipping['late_shipped_orders'], $shipping['shipped_orders']),

            'review_count' => $rating['review_count'],
            'avg_review_rating' => $rating['avg_review_rating'],

            'chat_count' => $response['chat_count'],
            'chat_responded_count' => $response['chat_responded_count'],
            'response_rate' => $this->rate($response['chat_responded_count'], $response['chat_count']),

            'dispute_rate' => $this->rate($dispute['disputed_returns'], $counts['returned_orders']),
        ];
    }

    /**
     * @return array{total_orders:int, cancelled_orders:int, delivered_orders:int, refunded_orders:int, returned_orders:int}
     */
    protected function orderCounts(int $sellerId, ?Carbon $start, Carbon $end): array
    {
        $q = Order::query()->where('seller_id', $sellerId);
        if ($start) {
            $q->whereBetween('created_at', [$start, $end]);
        }

        $agg = $q->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as cancelled,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as delivered,
                SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as refunded,
                SUM(CASE WHEN status IN (?, ?) THEN 1 ELSE 0 END) as returned
            ', [
            OrderStatus::CANCELLED->value,
            OrderStatus::DELIVERED->value,
            OrderStatus::REFUNDED->value,
            OrderStatus::RETURNED->value,
            OrderStatus::RETURN_APPROVED->value,
        ])
            ->first();

        return [
            'total_orders' => (int) ($agg->total ?? 0),
            'cancelled_orders' => (int) ($agg->cancelled ?? 0),
            'delivered_orders' => (int) ($agg->delivered ?? 0),
            'refunded_orders' => (int) ($agg->refunded ?? 0),
            'returned_orders' => (int) ($agg->returned ?? 0),
        ];
    }

    /**
     * Late = accepted → shipped exceeds SLA hours.
     * Uses order_status_logs to compute elapsed time.
     *
     * @return array{shipped_orders:int, late_shipped_orders:int, avg_shipping_hours:float|null}
     */
    protected function shippingMetrics(int $sellerId, ?Carbon $start, Carbon $end): array
    {
        $sla = (float) config('marketplace.performance.shipping_sla_hours', 48);

        $orderIds = Order::query()
            ->where('seller_id', $sellerId)
            ->when($start, fn ($q) => $q->whereBetween('created_at', [$start, $end]))
            ->whereIn('status', [
                OrderStatus::SHIPPED->value,
                OrderStatus::DELIVERED->value,
                OrderStatus::COMPLETED->value,
                OrderStatus::RETURN_REQUESTED->value,
                OrderStatus::RETURN_APPROVED->value,
                OrderStatus::RETURNED->value,
                OrderStatus::REFUNDED->value,
            ])
            ->pluck('id');

        if ($orderIds->isEmpty()) {
            return ['shipped_orders' => 0, 'late_shipped_orders' => 0, 'avg_shipping_hours' => null];
        }

        // Pull accepted and shipped transitions per order
        $logs = OrderStatusLog::query()
            ->whereIn('order_id', $orderIds)
            ->whereIn('new_status', [
                OrderStatus::ACCEPTED->value,
                OrderStatus::SHIPPED->value,
            ])
            ->orderBy('id')
            ->get(['order_id', 'new_status', 'created_at']);

        $byOrder = [];
        foreach ($logs as $log) {
            $statusValue = $log->new_status instanceof OrderStatus
                ? $log->new_status->value
                : (int) $log->new_status;
            $byOrder[$log->order_id][$statusValue] = Carbon::parse($log->created_at);
        }

        $shipped = 0;
        $late = 0;
        $hoursSum = 0.0;
        $hoursCount = 0;

        foreach ($byOrder as $events) {
            $acceptedAt = $events[OrderStatus::ACCEPTED->value] ?? null;
            $shippedAt = $events[OrderStatus::SHIPPED->value] ?? null;

            if (! $shippedAt) {
                continue;
            }

            $shipped++;

            if ($acceptedAt) {
                // Carbon's diffInHours($other, signed=false) is our reference value:
                // older - newer = negative, newer - older = positive.
                // We want "how long after accepted did the order ship" — always >= 0.
                $hours = $acceptedAt->diffInHours($shippedAt, true);
                $hoursSum += (float) $hours;
                $hoursCount++;

                if ($hours > $sla) {
                    $late++;
                }
            }
        }

        return [
            'shipped_orders' => $shipped,
            'late_shipped_orders' => $late,
            'avg_shipping_hours' => $hoursCount > 0 ? round($hoursSum / $hoursCount, 2) : null,
        ];
    }

    /**
     * @return array{review_count:int, avg_review_rating:float}
     */
    protected function reviewMetrics(int $sellerId, ?Carbon $start, Carbon $end): array
    {
        $q = Review::approved()->where('seller_id', $sellerId);
        if ($start) {
            $q->whereBetween('created_at', [$start, $end]);
        }

        $agg = $q->selectRaw('COUNT(*) as count, COALESCE(AVG(rating), 0) as avg_rating')->first();

        return [
            'review_count' => (int) ($agg->count ?? 0),
            'avg_review_rating' => round((float) ($agg->avg_rating ?? 0), 2),
        ];
    }

    /**
     * Counts: chat threads in the period. Responded: seller sent at least one message
     * after the customer's first message. avg_response_hours = time between customer's first
     * message and seller's first reply.
     *
     * Convention (from SellerChatController::sendMessage): seller messages are
     * stored with `user_id = NULL`, customer messages have `seller_id` unset.
     * We treat messages with `user_id IS NULL` as seller messages.
     *
     * @return array{chat_count:int, chat_responded_count:int, avg_response_hours:float|null}
     */
    protected function responseMetrics(int $sellerId, ?Carbon $start, Carbon $end): array
    {
        $chatQuery = SellerChat::query()->where('seller_id', $sellerId);
        if ($start) {
            $chatQuery->whereBetween('created_at', [$start, $end]);
        }

        $chats = $chatQuery->with(['messages' => function ($q) {
            $q->orderBy('created_at');
        }])->get(['id', 'seller_id', 'created_at']);

        $total = $chats->count();
        $responded = 0;
        $hoursSum = 0.0;
        $respondedCount = 0;

        foreach ($chats as $chat) {
            $messages = $chat->messages;
            if ($messages->isEmpty()) {
                continue;
            }

            // Identify direction: seller message has user_id === NULL
            $customerFirst = $messages->first(fn ($m) => $m->user_id !== null);
            $sellerFirst = $messages->first(fn ($m) => $m->user_id === null);

            if (! $sellerFirst) {
                continue;
            }

            $responded++;

            if ($customerFirst) {
                $hours = Carbon::parse($sellerFirst->created_at)
                    ->diffInHours(Carbon::parse($customerFirst->created_at), false);

                if ($hours > 0) {
                    $hoursSum += (float) $hours;
                    $respondedCount++;
                }
            }
        }

        return [
            'chat_count' => $total,
            'chat_responded_count' => $responded,
            'avg_response_hours' => $respondedCount > 0 ? round($hoursSum / $respondedCount, 2) : null,
        ];
    }

    /**
     * @return array{disputed_returns:int}
     */
    protected function disputeMetrics(int $sellerId, ?Carbon $start, Carbon $end): array
    {
        $returns = ReturnRequest::query()
            ->whereHas('order', fn ($q) => $q->where('seller_id', $sellerId))
            ->when($start, fn ($q) => $q->whereBetween('created_at', [$start, $end]))
            ->with('dispute')
            ->get(['id', 'is_disputed']);

        $disputed = $returns->filter(fn ($r) => $r->is_disputed && $r->dispute)->count();

        return ['disputed_returns' => $disputed];
    }

    protected function rate(int $numerator, int $denominator): float
    {
        if ($denominator <= 0) {
            return 0.0;
        }

        return round($numerator / $denominator, 4);
    }
}
