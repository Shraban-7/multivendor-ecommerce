<?php

namespace App\Domain\Order\Models;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosCartItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }

    public function pos_cart()
    {
        return $this->belongsTo(PosCart::class, 'pos_cart_id');
    }
}
