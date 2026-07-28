<?php

namespace App\Domain\Shipping\Models;

use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerShippingZone extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'rate' => 'decimal:2',
        'free_above' => 'decimal:2',
        'extra_rate_per_kg' => 'decimal:2',
        'min_weight' => 'decimal:2',
        'max_weight' => 'decimal:2',
        'min_order' => 'decimal:2',
        'max_order' => 'decimal:2',
        'districts' => 'array',
        'is_cod_available' => 'boolean',
        'is_active' => 'boolean',
        'estimated_days_min' => 'integer',
        'estimated_days_max' => 'integer',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(ShippingCarrier::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function types(): array
    {
        return [
            'flat' => 'Flat Rate',
            'weight_based' => 'Weight Based',
            'price_based' => 'Price Based',
        ];
    }
}
