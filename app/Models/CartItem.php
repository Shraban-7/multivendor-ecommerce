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

    public function getPriceAttribute()
    {
        if ($this->variant) {
            return $this->product->selling_price + $this->variant->price;
        }
        return $this->product->selling_price;
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->product) {
            return $this->product->getDiscountedPrice($this->price);
        }
        return $this->price;
    }
}
