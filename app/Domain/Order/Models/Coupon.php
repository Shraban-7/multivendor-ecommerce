<?php

namespace App\Domain\Order\Models;

use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'status' => 'boolean',
    ];

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'coupon_products');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->where('valid_from', '<=', now())
            ->where('valid_until', '>=', now());
    }

    public function scopeSellerCoupons($query, int $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    public function scopeGlobal($query)
    {
        return $query->whereNull('seller_id');
    }

    public function isValid(): bool
    {
        if (! $this->status) {
            return false;
        }

        if (now()->lt($this->valid_from)) {
            return false;
        }

        if (now()->gt($this->valid_until)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function isGlobal(): bool
    {
        return is_null($this->seller_id);
    }

    public function discountTypeLabel(): string
    {
        return match ($this->discount_type) {
            'percentage' => 'Percentage',
            'flat' => 'Flat Rate',
            default => ucfirst($this->discount_type),
        };
    }

    public function remainingUses(): ?int
    {
        if (! $this->usage_limit) {
            return null;
        }

        return max(0, $this->usage_limit - $this->used_count);
    }
}
