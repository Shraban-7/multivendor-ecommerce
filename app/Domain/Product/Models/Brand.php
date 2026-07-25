<?php

namespace App\Domain\Product\Models;

use Database\Factories\BrandFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory(): Factory
    {
        return BrandFactory::new();
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
