<?php

namespace App\Domain\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionValue extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'product_variant_ids' => 'array',
    ];

    public function product_attribute()
    {
        return $this->belongsTo(Option::class, 'product_attribute_id');
    }

    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id');
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class, 'product_variant_options');
    }
}
