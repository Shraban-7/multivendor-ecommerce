<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function product_attribute_options()
    {
        return $this->hasMany(ProductAttributeOption::class);
    }
}
