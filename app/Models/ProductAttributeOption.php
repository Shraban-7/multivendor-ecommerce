<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeOption extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'product_variant_ids' => 'array',
    ];

    public function product_attribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    // public function product_variants()
    // {
    //     return $this->belongsToMany(
    //         ProductVariant::class,
    //     );
    // }
}
