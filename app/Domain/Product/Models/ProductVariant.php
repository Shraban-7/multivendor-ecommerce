<?php

namespace App\Domain\Product\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
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

    /** Effective unit price for cart / order: compare_price if set, else price. */
    public function calculatedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->compare_price ?? $this->price
        );
    }

    public function calculatedDiscount(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->compare_price === null) {
                    return 0;
                }

                return max(0, (float) $this->price - (float) $this->compare_price);
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
