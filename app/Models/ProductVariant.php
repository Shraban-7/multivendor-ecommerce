<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'option_ids' => 'array',
    ];

    public function scopeWhereProduct($query, Product $product)
    {
        return $query->where('product_id', $product->id);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function optionValues()
    {
        return $this->belongsToMany(OptionValue::class, 'product_variant_options', 'product_variant_id', 'option_value_id')
            ->with('option');
    }

    public function options()
    {
        return $this->hasMany(ProductVariantOption::class, 'product_variant_id');
    }
}
