<?php

namespace App\Domain\Product\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'buying_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discounted_price' => 'decimal:2',
    ];

    public function scopeWhereProduct($query, Product $product)
    {
        return $query->where('product_id', $product->id);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function color()
    {
        return $this->belongsTo(Color::class);
    }

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class, 'product_variant_id');
    }

    public function label(): Attribute
    {
        return Attribute::make(
            get: function () {
                $parts = array_filter([
                    $this->color?->name,
                    $this->size?->name,
                ]);

                return implode(' / ', $parts) ?: 'Default';
            }
        );
    }

    public function availableStock(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stock_in - $this->stock_out
        );
    }

    public function calculatedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->discounted_price ? $this->discounted_price : $this->selling_price
        );
    }

    public function calculatedDiscount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $discountedPrice = $this->discounted_price ?? 0;
                $sellingPrice = $this->selling_price ?? 0;

                return $sellingPrice - $discountedPrice;
            }
        );
    }

    public function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image ? storage_url($this->image) : asset('assets/frontend/images/default.png')
        );
    }
}
