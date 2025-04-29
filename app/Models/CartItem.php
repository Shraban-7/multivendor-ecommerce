<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function getProductOriginalPriceAttribute()
    {
        $optionIds = json_decode($this->product_attribute_option_ids, true);

        $optionIds = is_null($optionIds) ? [] : $optionIds;

        $variant_price = ProductVariantProductAttributeOption::whereIn('product_attribute_option_id', $optionIds)
        ->where('product_variant_id', $this->product_variant_id)
            ->sum('additional_price');

        if ($this->variant) {
            return $this->product->selling_price+ $variant_price;
        }

        return $this->product->selling_price;
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->product) {
            return $this->product->getDiscountedPrice($this->product_original_price);
        }
        return $this->product_original_price;
    }
}
