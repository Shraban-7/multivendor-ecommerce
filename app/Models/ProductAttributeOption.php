<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributeOption extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }
}
