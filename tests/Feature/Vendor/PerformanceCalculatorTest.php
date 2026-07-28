<?php

use App\Domain\Vendor\Enums\PerformancePeriod;
use App\Domain\Vendor\Enums\PerformanceTier;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Services\PerformanceCalculatorService;

test('rating score: 5.0 -> 100, 0 -> 0, cap at 100', function () {
    $calculator = app(PerformanceCalculatorService::class);
    $reflection = new ReflectionClass($calculator);
    $method = $reflection->getMethod('scoreFromRating');
    $method->setAccessible(true);

    expect((float) $method->invoke($calculator, 5.0))->toBe(100.0)
        ->and((float) $method->invoke($calculator, 0))->toBe(0.0)
        ->and((float) $method->invoke($calculator, 4.0))->toBe(80.0)
        ->and((float) $method->invoke($calculator, 7.0))->toBe(100.0)
        ->and((float) $method->invoke($calculator, -1.0))->toBe(0.0);
});

test('rate-based score: full credit at zero, no credit at threshold, linear in between', function () {
    $calculator = app(PerformanceCalculatorService::class);
    $reflection = new ReflectionClass($calculator);
    $method = $reflection->getMethod('scoreFromRate');
    $method->setAccessible(true);

    expect((float) $method->invoke($calculator, 0.0, 0.2))->toBe(100.0)
        ->and((float) $method->invoke($calculator, 0.20, 0.2))->toBe(0.0)
        ->and((float) $method->invoke($calculator, 0.10, 0.2))->toBe(50.0)
        ->and((float) $method->invoke($calculator, 0.30, 0.2))->toBe(0.0);
});

test('tier mapping: thresholds & insufficient data', function () {
    expect(PerformanceTier::fromScore(95, 100))->toBe(PerformanceTier::EXCELLENT);
    expect(PerformanceTier::fromScore(75, 100))->toBe(PerformanceTier::GOOD);
    expect(PerformanceTier::fromScore(55, 100))->toBe(PerformanceTier::AVERAGE);
    expect(PerformanceTier::fromScore(20, 100))->toBe(PerformanceTier::POOR);
    expect(PerformanceTier::fromScore(99, 3))->toBe(PerformanceTier::NEW);
});

test('overall score: weighted sum with a healthy mix of metrics', function () {
    $calculator = app(PerformanceCalculatorService::class);

    $metrics = [
        'period' => 'last_30_days',
        'period_start' => now()->subDays(30)->toDateTimeString(),
        'period_end' => now()->toDateTimeString(),
        'total_orders' => 100,
        'cancelled_orders' => 5,
        'cancellation_rate' => 0.05, // 5/100
        'shipped_orders' => 80,
        'late_shipped_orders' => 8,
        'late_shipping_rate' => 0.10, // 8/80
        'delivered_orders' => 70,
        'refunded_orders' => 2,
        'returned_orders' => 10,
        'disputed_returns' => 1,
        'dispute_rate' => 0.10,
        'avg_shipping_hours' => 30,
        'avg_response_hours' => 12,
        'avg_review_rating' => 4.2,
        'review_count' => 30,
        'chat_count' => 40,
        'chat_responded_count' => 36,
        'response_rate' => 0.90,
    ];

    $seller = new Seller;
    $computed = $calculator->compute($seller, PerformancePeriod::LAST_30_DAYS, $metrics);

    expect($computed['overall_score'])->toBeGreaterThan(70.0)
        ->and($computed['overall_score'])->toBeLessThanOrEqual(100.0)
        ->and($computed['sub_scores']['cancellation_score'])->toBe(75.0) // max 0.20 → 1 - 0.05/0.20 = 0.75 → 75
        ->and($computed['sub_scores']['late_shipping_score'])->toBe(66.67) // max 0.30 → 1 - 0.10/0.30 = 0.6666
        ->and($computed['sub_scores']['rating_score'])->toBe(84.0)
        ->and($computed['sub_scores']['response_score'])->toBe(90.0)
        ->and($computed['tier'])->toBeIn(['excellent', 'good']);
});

test('overall score: poor seller falls into "poor" tier', function () {
    config([
        'marketplace.performance.min_orders_for_scoring' => 1,
    ]);
    $calculator = app(PerformanceCalculatorService::class);

    $metrics = [
        'total_orders' => 50,
        'cancelled_orders' => 25,
        'shipped_orders' => 20,
        'late_shipped_orders' => 18,
        'delivered_orders' => 10,
        'returned_orders' => 12,
        'disputed_returns' => 6,
        'avg_review_rating' => 1.5,
        'review_count' => 10,
        'chat_count' => 30,
        'chat_responded_count' => 4,
        'cancellation_rate' => 0.50,
        'late_shipping_rate' => 0.90,
        'response_rate' => 0.13,
        'dispute_rate' => 0.50,
    ];

    $seller = new Seller;
    $computed = $calculator->compute($seller, PerformancePeriod::LAST_30_DAYS, $metrics);

    expect($computed['overall_score'])->toBeLessThan(50)
        ->and($computed['tier'])->toBe('poor');
    config(['marketplace.performance.min_orders_for_scoring' => 5]);
});
