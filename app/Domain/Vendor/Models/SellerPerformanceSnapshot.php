<?php

namespace App\Domain\Vendor\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $seller_id
 * @property string $snapshot_date
 * @property int $total_orders
 * @property int $cancelled_orders
 * @property int $late_shipped_orders
 * @property int $delivered_orders
 * @property int $review_count
 * @property float $avg_review_rating
 * @property float $cancellation_rate
 * @property float $late_shipping_rate
 * @property float $overall_score
 * @property string $tier
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class SellerPerformanceSnapshot extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'snapshot_date' => 'date',
        'avg_review_rating' => 'float',
        'cancellation_rate' => 'float',
        'late_shipping_rate' => 'float',
        'overall_score' => 'float',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
