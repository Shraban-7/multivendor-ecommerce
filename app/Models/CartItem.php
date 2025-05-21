<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'product_variant_ids' => 'array',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'variants', 'id', 'id')
            ->whereIn('id', $this->product_variant_ids ?? []);
    }

    public function getProductOriginalPriceAttribute()
    {
        $variantIds = $this->product_variant_ids;

        if (! is_array($variantIds)) {
            $variantIds = is_null($variantIds) ? [] : json_decode($variantIds, true);
        }
        $variantPrice = ProductVariant::whereIn('id', $variantIds)->sum('additional_price');

        return $this->product->selling_price + $variantPrice;
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->product) {
            return $this->product->getDiscountedPrice($this->product_original_price);
        }
        return $this->product_original_price;
    }

    public function getVariantOptionAttribute()
    {
        if (! $this->product_variant_ids) {
            return collect();
        }

        $variantIds = is_array($this->product_variant_ids)
        ? $this->product_variant_ids
        : json_decode($this->product_variant_ids, true);

        return ProductVariant::with('option.productAttribute')
            ->whereIn('id', $variantIds)
            ->get()
            ->map(function ($variant) {
                return [
                    'productAttribute' => $variant->option->product_attribute->name ?? null,
                    'option'           => $variant->option->value ?? null,
                ];
            });
    }

}
