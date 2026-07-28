<?php

namespace App\Domain\Shipping\Models;

use App\Domain\Order\Models\Order;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shipment extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'weight' => 'float',
        'shipping_cost' => 'float',
        'cod_amount' => 'float',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_PICKED_UP = 'picked_up';
    public const STATUS_IN_TRANSIT = 'in_transit';
    public const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_FAILED = 'failed';
    public const STATUS_RETURNED = 'returned';

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PICKED_UP => 'Picked Up',
            self::STATUS_IN_TRANSIT => 'In Transit',
            self::STATUS_OUT_FOR_DELIVERY => 'Out for Delivery',
            self::STATUS_DELIVERED => 'Delivered',
            self::STATUS_FAILED => 'Failed',
            self::STATUS_RETURNED => 'Returned',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(ShippingCarrier::class, 'shipping_carrier_id');
    }

    public function trackingLogs(): HasMany
    {
        return $this->hasMany(TrackingLog::class);
    }

    public function scopeForSeller($query, int $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
