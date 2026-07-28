<?php

namespace App\Domain\Vendor\Models;

use App\Domain\Vendor\Enums\PerformancePeriod;
use App\Domain\Vendor\Enums\PerformanceTier;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $seller_id
 * @property string $period
 * @property Carbon|null $period_start
 * @property Carbon|null $period_end
 * @property int $total_orders
 * @property int $cancelled_orders
 * @property int $shipped_orders
 * @property int $late_shipped_orders
 * @property int $delivered_orders
 * @property int $refunded_orders
 * @property int $returned_orders
 * @property int $disputed_returns
 * @property int $review_count
 * @property int $chat_count
 * @property int $chat_responded_count
 * @property float $avg_review_rating
 * @property float|null $avg_shipping_hours
 * @property float|null $avg_response_hours
 * @property float $cancellation_rate
 * @property float $late_shipping_rate
 * @property float $response_rate
 * @property float $dispute_rate
 * @property float $cancellation_score
 * @property float $late_shipping_score
 * @property float $rating_score
 * @property float $response_score
 * @property float $dispute_score
 * @property float $overall_score
 * @property string $tier
 * @property array|null $breakdown
 * @property array|null $weights
 * @property array|null $thresholds
 * @property Carbon $computed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SellerPerformanceScore extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'computed_at' => 'datetime',
        'breakdown' => 'array',
        'weights' => 'array',
        'thresholds' => 'array',
        'avg_review_rating' => 'float',
        'avg_shipping_hours' => 'float',
        'avg_response_hours' => 'float',
        'cancellation_rate' => 'float',
        'late_shipping_rate' => 'float',
        'response_rate' => 'float',
        'dispute_rate' => 'float',
        'cancellation_score' => 'float',
        'late_shipping_score' => 'float',
        'rating_score' => 'float',
        'response_score' => 'float',
        'dispute_score' => 'float',
        'overall_score' => 'float',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function periodEnum(): PerformancePeriod
    {
        return PerformancePeriod::from($this->period);
    }

    public function tierEnum(): PerformanceTier
    {
        return PerformanceTier::from($this->tier);
    }

    public function tierColor(): string
    {
        return $this->tierEnum()->color();
    }

    public function tierLabel(): string
    {
        return $this->tierEnum()->label();
    }

    protected function grade(): Attribute
    {
        return Attribute::make(
            get: function () {
                $score = (float) $this->overall_score;

                return match (true) {
                    $score >= 85 => 'A',
                    $score >= 70 => 'B',
                    $score >= 50 => 'C',
                    default => 'D',
                };
            }
        );
    }
}
