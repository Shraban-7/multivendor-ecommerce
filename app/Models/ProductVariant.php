<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    

    public function scopeWhereProduct($query,Product $product)
    {
        return $query->where('product_id',$product->id);
    }
}
