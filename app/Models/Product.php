<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'lightdeal_expired__at' => 'datetime',
    ];

    public function scopeLightDeal($query)
    {
        return $query->where('is_lightdeal',true);
    }
    public function scopeInterest($query)
    {
        return $query->where('is_interest',true);
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending',true);
    }

    public function scopeCommunity($query)
    {
        return $query->where('is_community',true);
    }

    public function scopeWhereCategory($query,Category $category)
    {
        return $query->where('category_id',$category->id);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function product_attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
