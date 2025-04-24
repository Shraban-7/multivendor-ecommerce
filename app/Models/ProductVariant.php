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

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeOptions()
    {
        return $this->belongsToMany(
            ProductAttributeOption::class,
            'product_variant_product_attribute_options',
            'product_variant_id',
            'product_attribute_option_id'
        );
    }
}
