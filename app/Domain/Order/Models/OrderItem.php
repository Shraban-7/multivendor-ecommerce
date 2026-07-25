<?php

namespace App\Domain\Order\Models;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Review\Models\Review;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'quantity' => 'integer',
        'is_reviewed' => 'integer',
        'subtotal' => 'float',
        'unit_price' => 'float',
        'discount' => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id')
            ->with('option_values.option');
    }

    public function getOriginalPriceAttribute()
    {
        if ($this->quantity > 0) {
            return $this->unit_price + ($this->discount / $this->quantity);
        }

        return $this->unit_price;
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_item_id');
    }
}
