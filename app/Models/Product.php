<?php

namespace App\Models;

use App\Enums\DiscountType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'lightdeal_expired_at' => 'datetime',
    ];

    public function scopeLightDeal($query)
    {
        return $query->where('is_lightdeal', true);
    }
    public function scopeInterest($query)
    {
        return $query->where('is_interest', true);
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
    }

    public function scopeCommunity($query)
    {
        return $query->where('is_community', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeWhereCategory($query, Category $category)
    {
        return $query->where('category_id', $category->id);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function subcategory()
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
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

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function unit()
    {
        return $this->belongsTo(ProductUnit::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function toDetailsArray()
    {
        $this->load('images', 'category', 'subcategory', 'variants.attributeOptions.productAttribute');

        $sold = OrderItem::where('product_id', $this->id)->count();
        $revenue = $sold * $this->selling_price;
        $profit = $revenue - ($sold * $this->buying_price);
        $lastOrder = OrderItem::where('product_id', $this->id)->latest('created_at')->first();
        $lastSale = $lastOrder?->created_at;
        $stockHistory = StockHistory::where('product_id', $this->id)->latest()->get();
        $margin = $this->selling_price - $this->buying_price;
        $marginPercent = $this->buying_price > 0 ? ($margin / $this->buying_price) * 100 : 0;

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'category' => $this->category?->name,
            'subcategory' => $this->subcategory?->name,
            'brand' => $this->brand?->name,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail,
            'images' => $this->images->pluck('image'),
            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => $this->selling_price,
            'buying_cost' => $this->buying_price,
            'discount_price' => money($this->discounted_price),
            'discount' => [
                'type' => $this->discount_type,
                'amount' => money($this->discount),
                'percent' => money($this->discount_percent),
            ],
            'stock_status' => $this->stock_status,
            'in_stock' => $this->stock_in,
            'sold_out' => $this->stock_out,
            'variants' => $this->variants->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'sku' => $variant->sku,
                    'stock' => $variant->stock,
                    'price' => $variant->price,
                    'attributes' => $variant->attributeOptions->map(function ($option) {
                        return [
                            'attribute_id' => $option->productAttribute->id,
                            'name' => $option->productAttribute->name,
                            'options' => [
                                'id' => $option->id,
                                'value' => $option->value ?? null,
                            ]
                        ];
                    }),
                ];
            }),
            'total_sold' => $sold,
            'revenue' => $revenue,
            'profit' => $profit,
            'last_sale' => $lastSale,
            'stock_history' => $stockHistory,
            'profit' => [
                'margin' => (float) $margin,
                'percent' => round($marginPercent, 2),
            ],
            'rating' => number_format($this->reviews->avg('rating'), 1),
            'total_reviews' => $this->reviews->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function getDiscountedPrice($basePrice)
    {
        if ($this->discount_type !== null) {
            if ($this->discount_type === \App\Enums\DiscountType::FLAT) {
                return $basePrice - $this->discount_amount;
            } elseif ($this->discount_type === \App\Enums\DiscountType::PERCENTAGE) {
                return $basePrice - (($basePrice * $this->discount_amount) / 100);
            }
        }

        return $basePrice;
    }

    public function getDiscountedPriceAttribute()
    {
        return $this->getDiscountedPrice($this->selling_price);
    }

    public function getDiscountedPriceWithVariantAttribute()
    {
        return $this->variant
            ? $this->getDiscountedPrice($this->variant->price+$this->selling_price)
            : $this->discounted_price;
    }

    public function getDiscountAttribute()
    {
        $basePrice = $this->variant
            ? $this->variant->price + $this->selling_price
            : $this->selling_price;

        if ($this->discount_type !== null) {
            if ($this->discount_type === \App\Enums\DiscountType::FLAT) {
                return $this->discount_amount;
            } elseif ($this->discount_type === \App\Enums\DiscountType::PERCENTAGE) {
                return ($basePrice * $this->discount_amount) / 100;
            }
        }

        return 0;
    }

    public function getDiscountPercentAttribute()
    {
        if (!$this->discount_type) {
            return 0;
        }

        return round(($this->discount / $this->selling_price) * 100, 2);
    }
}
