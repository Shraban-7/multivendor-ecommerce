<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function scopeNav($query)
    {
        return $query->where('is_nav',true)->where('status', true);
    }

    public function scopeCategory($query)
    {
        return $query->whereNull('category_id');
    }

    public function scopeSubcategory($query)
    {
        return $query->whereNotNull('category_id');
    }

    public function scopeAllDepartment($query)
    {
        return $query->whereNull('category_id')->where('is_nav', false)->where('status', true);
    }

    public function scopeSlider($query) {
        return $query->where('is_slider',true)->where('status', true);
    }
    public function scopeSpecial($query) {
        return $query->where('is_special',true)->where('status', true);
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'category_id');
    }

    public function banners()
    {
        return $this->hasMany(CategoryBanner::class, 'category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
