<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    const MATERIAL = 'material';
    const COLOR = 'color';
    const SIZE = 'size';

    protected $guarded = ['id'];

    public function scopeMaterial($query)
    {
        return $query->where('name',ProductAttribute::MATERIAL);
    }

    public function product_attribute_options()
    {
        return $this->hasMany(ProductAttributeOption::class);
    }
}
