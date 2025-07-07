<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function sellerCampaigns()
    {
        return $this->belongsToMany(SellerCampaign::class, 'seller_campaign_product')
            ->using(SellerCampaignProduct::class)
            ->withTimestamps();
    }

    public function toDetailsArray()
    {
        $this->load('images', 'category', 'subcategory', 'variants', 'seller', 'reviews.user');

        $sold                   = OrderItem::where('product_id', $this->id)->count();
        $revenue                = $sold * $this->selling_price;
        $profit                 = $revenue - ($sold * $this->buying_price);
        $lastOrder              = OrderItem::where('product_id', $this->id)->latest('created_at')->first();
        $lastSale               = $lastOrder?->created_at;
        $stockHistory           = StockHistory::where('product_id', $this->id)->latest()->get();
        $margin                 = $this->selling_price - $this->buying_price;
        $marginPercent          = $this->buying_price > 0 ? ($margin / $this->buying_price) * 100 : 0;
        return [
            'id'                => $this->id,
            'slug'              => $this->slug,
            'sku'               => $this->sku,
            'category_id'       => $this->category?->id,
            'category'          => $this->category?->name,
            'subcategory'       => $this->subcategory?->name,
            'brand'             => $this->brand?->name,
            'name'              => $this->name,
            'thumbnail'         => $this->thumbnail,
            'images'            => $this->images->pluck('image'),
            'slider'            => collect([
                $this->thumbnail,
            ])
                ->filter()
                ->concat($this->images->pluck('image'))
                ->concat(
                    $this->variants->pluck('image')->filter()
                )
                ->unique()
                ->values(),
            'short_description' => $this->short_description,
            'description'       => $this->description,
            'price'             => $this->selling_price,
            'discounted_price'  => $this->discounted_price,
            'buying_cost'       => $this->cost_price,
            'discount'          => [
                'type'  => $this->discount_type,
                'value' => $this->discount_value,
                'price' => $this->discount_amount,
            ],
            'stock_status'      => $this->stock_status,
            'in_stock'          => $this->stock_in,
            'sold_out'          => $this->stock_out,
            'almost_sold_out'   => ($this->stock_in - $this->stock_out) <= $this->low_stock_quantity ? true : false,
            'variants'          => $this->variants->map(function ($variant) {
                return [
                    'id'               => $variant->id,
                    'sku'              => $variant->sku,
                    'stock'            => $variant->stock_in - $variant->stock_out,
                    'price'            => $variant->selling_price,
                    'discounted_price' => $variant->discounted_price,
                    'image'            => $variant->image,
                    'value_ids'        => $variant->optionValues->pluck('id')->sort()->values()->toArray(),
                ];
            }),
            'options'           => $this->grouped_options,
            'defaultVariant'    => collect($this['variants'])->firstWhere('stock', '>', 0),

            'total_sold'        => $sold,
            'revenue'           => $revenue,
            'profit'            => $profit,
            'last_sale'         => $lastSale,
            'stock_history'     => $stockHistory,    
            'profit'            => [
                'margin'  => (float) $margin,
                'percent' => round($marginPercent, 2),
            ],
            'seller'            => [
                'id'            => $this->seller->id,
                'username'      => $this->seller->username,
                'business_name' => $this->seller->business_name,
                'business_logo' => $this->seller->business_logo,
                'best_seller'   => $this->seller->is_best_seller,
            ],
            'reviews'           => $this->reviews,
            'rating'            => number_format($this->reviews->avg('rating'), 1),
            'total_reviews'     => $this->reviews->count(),
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }

    public function groupedOptions(): Attribute
    {
        return Attribute::get(function () {
            return $this->variants
                ->flatMap(fn($variant) => $variant->optionValues)
                ->groupBy(fn($val) => $val->option->id)
                ->map(function ($group) {
                    $option = $group->first()->option;

                    return [
                        'id' => $option->id,
                        'name' => $option->name,
                        'values' => $group->unique('id')->map(fn($v) => [
                            'id' => $v->id,
                            'value' => $v->value,
                        ])->values()->toArray(),
                    ];
                })
                ->values()
                ->toArray();
        });
    }
}
