<?php

namespace App\Domain\Bundle\Models;

use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Bundle extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'is_visible' => 'boolean',
        'total_stock' => 'integer',
    ];

    public const STATUS_PENDING_APPROVAL = 0;
    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 2;
    public const STATUS_DRAFT = 3;

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(BundleItem::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(BundleImage::class);
    }

    public function pricingRules(): HasMany
    {
        return $this->hasMany(BundlePricingRule::class);
    }

    public function statusName(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                self::STATUS_PENDING_APPROVAL => 'Pending Approval',
                self::STATUS_ACTIVE => 'Active',
                self::STATUS_INACTIVE => 'Inactive',
                self::STATUS_DRAFT => 'Draft',
                default => null,
            }
        );
    }

    public function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->thumbnail
                ? storage_url($this->thumbnail)
                : asset('assets/frontend/images/placeholder-img.jpg')
        );
    }

    public function scopeForSeller($query, int $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function calculateStock(): int
    {
        if ($this->items->isEmpty()) {
            return 0;
        }

        $stock = null;
        foreach ($this->items as $item) {
            $product = $item->product;
            if (! $product) {
                return 0;
            }
            $available = (int) $product->stock_in - (int) $product->stock_out;
            $itemStock = (int) floor($available / max($item->quantity, 1));
            if ($stock === null || $itemStock < $stock) {
                $stock = $itemStock;
            }
        }

        return max($stock ?? 0, 0);
    }

    public function calculatePrice(): float
    {
        if ($this->price_type === 'manual' && $this->price !== null) {
            return (float) $this->price;
        }

        $total = 0.0;
        foreach ($this->items as $item) {
            if ($item->product) {
                $total += (float) $item->product->price * (int) $item->quantity;
            }
        }

        if ($this->discount_type === 'percentage' && $this->discount_value > 0) {
            $total -= $total * ((float) $this->discount_value / 100);
        } elseif ($this->discount_type === 'fixed' && $this->discount_value > 0) {
            $total -= (float) $this->discount_value;
        }

        return round(max($total, 0), 2);
    }

    public function savingsAmount(): float
    {
        $original = $this->calculateOriginalTotal();
        $current = $this->calculatePrice();

        return round(max($original - $current, 0), 2);
    }

    public function calculateOriginalTotal(): float
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            if ($item->product) {
                $total += (float) $item->product->price * (int) $item->quantity;
            }
        }
        return $total;
    }

    public static function generateSku(int $sellerId): string
    {
        return DB::transaction(function () use ($sellerId) {
            $seller = DB::table('sellers')
                ->where('id', $sellerId)
                ->lockForUpdate()
                ->first();

            $next = (int) $seller->sku_counter + 1;

            DB::table('sellers')
                ->where('id', $sellerId)
                ->update(['sku_counter' => $next]);

            return 'BND-' . $seller->code . str_pad($next, 5, '0', STR_PAD_LEFT);
        });
    }
}
