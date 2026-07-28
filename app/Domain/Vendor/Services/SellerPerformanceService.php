<?php

namespace App\Domain\Vendor\Services;

use App\Domain\Vendor\Enums\PerformancePeriod;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerPerformanceScore;
use App\Domain\Vendor\Models\SellerPerformanceSnapshot;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class SellerPerformanceService
{
    public function __construct(
        protected PerformanceMetricsService $metrics,
        protected PerformanceCalculatorService $calculator,
    ) {}

    /**
     * Recompute all configured periods for one seller.
     */
    public function recompute(Seller $seller, bool $withSnapshot = true): array
    {
        $periods = [
            PerformancePeriod::LAST_7_DAYS,
            PerformancePeriod::LAST_30_DAYS,
            PerformancePeriod::LAST_90_DAYS,
            PerformancePeriod::ALL_TIME,
        ];

        $results = [];
        foreach ($periods as $period) {
            $metrics = $this->metrics->metricsFor($seller, $period);
            $computed = $this->calculator->compute($seller, $period, $metrics);
            $score = $this->calculator->persist($seller, $period, $computed);

            if ($withSnapshot && $period === PerformancePeriod::LAST_30_DAYS) {
                $this->calculator->snapshot($seller, $score);
            }

            $results[$period->value] = $score;
            $this->forgetCache($seller->id, $period);
        }

        return $results;
    }

    public function score(Seller $seller, PerformancePeriod $period): SellerPerformanceScore
    {
        $cacheKey = $this->cacheKey($seller->id, $period);

        return Cache::remember($cacheKey, 300, function () use ($seller, $period) {
            $existing = SellerPerformanceScore::query()
                ->where('seller_id', $seller->id)
                ->where('period', $period->value)
                ->first();

            if ($existing && $existing->computed_at && $existing->computed_at->gt(now()->subHours(6))) {
                return $existing;
            }

            $this->metrics->metricsFor($seller, $period);
            $metrics = $this->metrics->metricsFor($seller, $period);
            $computed = $this->calculator->compute($seller, $period, $metrics);
            $this->calculator->persist($seller, $period, $computed);

            return SellerPerformanceScore::query()
                ->where('seller_id', $seller->id)
                ->where('period', $period->value)
                ->firstOrFail();
        });
    }

    public function trend(Seller $seller, int $days = 30): Collection
    {
        return SellerPerformanceSnapshot::query()
            ->where('seller_id', $seller->id)
            ->where('snapshot_date', '>=', now()->subDays($days)->toDateString())
            ->orderBy('snapshot_date')
            ->get();
    }

    public function leaderboard(PerformancePeriod $period, int $limit = 50): Collection
    {
        return SellerPerformanceScore::query()
            ->with('seller:id,business_name,username,code,image,rating,rating_count')
            ->where('period', $period->value)
            ->where('overall_score', '>', 0)
            ->orderByDesc('overall_score')
            ->limit($limit)
            ->get();
    }

    public function forgetCache(int $sellerId, ?PerformancePeriod $period = null): void
    {
        if ($period) {
            Cache::forget($this->cacheKey($sellerId, $period));

            return;
        }

        foreach (PerformancePeriod::cases() as $p) {
            Cache::forget($this->cacheKey($sellerId, $p));
        }
    }

    protected function cacheKey(int $sellerId, PerformancePeriod $period): string
    {
        return "seller.performance.{$sellerId}.{$period->value}";
    }
}
