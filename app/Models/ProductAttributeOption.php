<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeOption extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function product_attribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function product_variants()
    {
        return $this->belongsToMany(
            ProductVariant::class,
            'product_variant_product_attribute_options',
            'product_attribute_option_id',
            'product_variant_id'
        );
    }
}
