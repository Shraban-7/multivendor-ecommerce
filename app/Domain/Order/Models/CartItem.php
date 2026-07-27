<?php

namespace App\Domain\Order\Models;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id')
            ->with('color', 'size');
    }

    /** List / regular price (strikethrough when on sale). */
    public function getOriginalPriceAttribute()
    {
        return $this->variant?->price ?? $this->product->price;
    }

    /** Effective paid unit price: compare_price ?? price. */
    public function getDiscountedPriceAttribute()
    {
        if ($this->variant) {
            return $this->variant->compare_price ?? $this->variant->price;
        }

        return $this->product->compare_price ?? $this->product->price;
    }
}
