<?php

namespace App\Domain\Vendor\Services;

use App\Domain\Vendor\Enums\PerformancePeriod;
use App\Domain\Vendor\Enums\PerformanceTier;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerPerformanceScore;
use App\Domain\Vendor\Models\SellerPerformanceSnapshot;

/**
 * Aggregates metrics into a 0..100 score and assigns a tier.
 * Pure transformation — no DB reads beyond what's passed in.
 */
class PerformanceCalculatorService
{
    public function compute(Seller $seller, PerformancePeriod $period, array $metrics): array
    {
        $weights = $this->normaliseWeights(config('marketplace.performance.weights', []));
        $thresholds = config('marketplace.performance.thresholds', []);

        $subScores = [
            'cancellation_score' => $this->scoreFromRate(
                (float) ($metrics['cancellation_rate'] ?? 0),
                (float) ($thresholds['cancellation_max'] ?? 0.20)
            ),
            'late_shipping_score' => $this->scoreFromRate(
                (float) ($metrics['late_shipping_rate'] ?? 0),
                (float) ($thresholds['late_shipping_max'] ?? 0.30)
            ),
            'rating_score' => $this->scoreFromRating((float) ($metrics['avg_review_rating'] ?? 0)),
            'response_score' => round(((float) ($metrics['response_rate'] ?? 0)) * 100, 2),
            'dispute_score' => $this->scoreFromRate(
                (float) ($metrics['dispute_rate'] ?? 0),
                (float) ($thresholds['dispute_max'] ?? 0.30)
            ),
        ];

        $overall = 0.0;
        foreach ($subScores as $key => $value) {
            $weightKey = str_replace('_score', '', $key);
            $overall += $value * (float) ($weights[$weightKey] ?? 0);
        }
        $overall = round(min(100.0, max(0.0, $overall)), 2);

        $minOrders = (int) config('marketplace.performance.min_orders_for_scoring', 5);
        $tier = PerformanceTier::fromScore($overall, (int) ($metrics['total_orders'] ?? 0));

        return [
            'metrics' => $metrics,
            'sub_scores' => $subScores,
            'overall_score' => $overall,
            'tier' => $tier->value,
            'weights' => $weights,
            'thresholds' => $thresholds,
            'breakdown' => $this->explanation($subScores, $weights, $overall, $tier, $metrics, $minOrders),
        ];
    }

    public function persist(Seller $seller, PerformancePeriod $period, array $computed): SellerPerformanceScore
    {
        $metrics = $computed['metrics'];
        $subScores = $computed['sub_scores'];

        return SellerPerformanceScore::updateOrCreate(
            ['seller_id' => $seller->id, 'period' => $period->value],
            array_merge(
                [
                    'period_start' => $metrics['period_start'] ?? null,
                    'period_end' => $metrics['period_end'] ?? null,
                    'seller_id' => $seller->id,
                    'period' => $period->value,
                ],
                $metrics,
                $subScores,
                [
                    'overall_score' => $computed['overall_score'],
                    'tier' => $computed['tier'],
                    'weights' => $computed['weights'],
                    'thresholds' => $computed['thresholds'],
                    'breakdown' => $computed['breakdown'],
                    'computed_at' => now(),
                ]
            )
        );
    }

    public function snapshot(Seller $seller, SellerPerformanceScore $score): SellerPerformanceSnapshot
    {
        return SellerPerformanceSnapshot::updateOrCreate(
            ['seller_id' => $seller->id, 'snapshot_date' => today()->toDateString()],
            [
                'total_orders' => $score->total_orders,
                'cancelled_orders' => $score->cancelled_orders,
                'late_shipped_orders' => $score->late_shipped_orders,
                'delivered_orders' => $score->delivered_orders,
                'review_count' => $score->review_count,
                'avg_review_rating' => $score->avg_review_rating,
                'cancellation_rate' => $score->cancellation_rate,
                'late_shipping_rate' => $score->late_shipping_rate,
                'overall_score' => $score->overall_score,
                'tier' => $score->tier,
            ]
        );
    }

    /**
     * Linear penalty: at max threshold the score is 0; at 0% rate the score is 100.
     * Above the max threshold, score is 0 (capped).
     */
    protected function scoreFromRate(float $rate, float $maxThreshold): float
    {
        if ($maxThreshold <= 0) {
            return $rate <= 0 ? 100.0 : 0.0;
        }

        if ($rate <= 0) {
            return 100.0;
        }

        if ($rate >= $maxThreshold) {
            return 0.0;
        }

        return round(100 * (1 - ($rate / $maxThreshold)), 2);
    }

    protected function scoreFromRating(float $avg): float
    {
        if ($avg <= 0) {
            return 0.0;
        }

        // 5.0 → 100, 0 → 0. Anything above 5 is clamped.
        return round(min(100.0, max(0.0, ($avg / 5.0) * 100)), 2);
    }

    /**
     * @return array<string,float>
     */
    protected function normaliseWeights(array $weights): array
    {
        $sum = array_sum($weights);
        if ($sum <= 0) {
            // Fallback to equal weights
            return [
                'cancellation' => 0.30,
                'late_shipping' => 0.25,
                'rating' => 0.25,
                'response' => 0.10,
                'dispute' => 0.10,
            ];
        }

        $normalised = [];
        foreach ($weights as $k => $v) {
            $normalised[$k] = round($v / $sum, 4);
        }

        return $normalised;
    }

    protected function explanation(
        array $subScores,
        array $weights,
        float $overall,
        PerformanceTier $tier,
        array $metrics,
        int $minOrders,
    ): array {
        $orderCount = (int) ($metrics['total_orders'] ?? 0);
        $stages = [];
        foreach ($subScores as $key => $score) {
            $weightKey = str_replace('_score', '', $key);
            $stages[] = [
                'metric' => $weightKey,
                'sub_score' => (float) $score,
                'weight' => (float) ($weights[$weightKey] ?? 0),
                'weighted' => round($score * (float) ($weights[$weightKey] ?? 0), 2),
            ];
        }

        return [
            'formula' => 'overall = Σ(sub_score × weight)',
            'insufficient_data' => $orderCount < $minOrders,
            'min_orders_required' => $minOrders,
            'components' => $stages,
            'tier' => [
                'name' => $tier->value,
                'label' => $tier->label(),
                'color' => $tier->color(),
            ],
        ];
    }
}
