<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'quantity' => 'integer'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function getOriginalPriceAttribute()
    {
        return $this->variant?->selling_price ?? $this->product->selling_price;
    }

    public function getDiscountedPriceAttribute()
    {
        if ($this->variant) {
            if ($this->variant->discounted_price !== null) {
                return $this->variant->discounted_price;
            }
          return  $this->variant->selling_price;

        }else{
            return $this->product->discounted_price ?? $this->product->selling_price;
        }

    }
}
