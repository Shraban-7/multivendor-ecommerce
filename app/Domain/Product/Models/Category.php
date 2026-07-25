<?php

namespace App\Domain\Product\Models;

use App\Models\CategoryBanner;
use App\Models\Seller;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function newFactory(): Factory
    {
        return CategoryFactory::new();
    }

    public function scopeNav($query)
    {
        return $query->where('is_nav', true)->where('status', true);
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

    public function scopeSlider($query)
    {
        return $query->where('is_slider', true)->where('status', true);
    }

    public function scopeSpecial($query)
    {
        return $query->where('is_special', true)->where('status', true);
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

    public function sellers()
    {
        return $this->belongsToMany(Seller::class);
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'category_options');
    }

    public function iconUrl(): Attribute
    {
        $url = is_null($this->app_icon) ? asset('assets/frontend/images/category-placeholder.svg') : storage_url($this->app_icon);

        return Attribute::make(
            get: fn () => $url
        );
    }
}
