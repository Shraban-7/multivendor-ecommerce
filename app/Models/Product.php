<?php

namespace App\Models;

use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'payment_type' => PaymentType::class,
        'buying_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'discount_value' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'discounted_price' => 'decimal:2',
    ];

    const STATUS_PENDING_APPROVAL = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;
    const STATUS_DELETED = 3;

    public function statusName(): Attribute
    {
        $statusName = null;
        switch ($this->status) {
            case self::STATUS_PENDING_APPROVAL:
                $statusName = 'Pending Approval';
                break;
            case self::STATUS_ACTIVE:
                $statusName = 'Active';
                break;
            case self::STATUS_INACTIVE:
                $statusName = 'Inactive';
                break;
            case self::STATUS_DELETED:
                $statusName = 'Deleted';
                break;
        }

        return Attribute::make(
            get: fn() => $statusName
        );
    }

    public function scopeLightDeal($query)
    {
        return $query->where('is_lightdeal', true);
    }

    public function scopeTrending($query)
    {
        return $query->where('is_trending', true);
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

    public function stock_history()
    {
        return $this->hasMany(StockHistory::class, 'product_id');
    }

    public function seo(): HasOne
    {
        return $this->hasOne(ProductSeo::class);
    }

    public function toDetailsArray()
    {
        $margin = $this->selling_price - $this->buying_price;
        $marginPercent = $this->buying_price > 0 ? ($margin / $this->buying_price) * 100 : 0;

        $sold = $this->variants->sum('stock_out');

        $reviews = $this->reviews;

        $images = [];
        $images[] = $this->thumbnail;
        foreach ($this->images as $img) {
            $images[] = $img->image;
        }

        foreach ($this->variants as $variant) {
            if (!is_null($variant->image)) {
                $images[] = $variant->image;
            }
        }

        return [
            'id'                => $this->id,
            'slug'              => $this->slug,
            'sku'               => $this->sku,
            'category_id'       => $this->category?->id,
            'category'          => $this->category?->name,
            'subcategory'       => $this->subcategory?->name,
            'brand'             => $this->brand?->name,
            'name'              => $this->name,
            'thumbnail'         => $this->imageUrl,
            'images'            => $images,
            'slider' => $images,

            'short_description' => $this->short_description,
            'description' => $this->description,
            'selling_price' => $this->selling_price,
            'discounted_price' => $this->discounted_price,
            'stock_status' => $this->stock_status,
            'in_stock' => $this->stock_in,
            'sold_out' => $this->stock_out,
            'stock' => $this->stock_in - $this->stock_out,
            'almost_sold_out' => ($this->stock_in - $this->stock_out) <= $this->low_stock_quantity ? true : false,
            'variants' => $this->variants->map(function ($variant) {
                return [
                    'id'               => $variant->id,
                    'sku'              => $variant->sku,
                    'stock'            => $variant->stock_in - $variant->stock_out,
                    'price'            => $variant->selling_price,
                    'discounted_price' => $variant->discounted_price,
                    'image'            => $variant->image,
                    'value_ids'        => $variant->option_values->pluck('id')->sort()->values()->toArray(),
                    'is_default'       => $variant->is_default,
                ];
            }),
            'options'           => $this->grouped_options,
            'default_variant'    => collect($this['variants'])->sortByDesc('is_default')->first(),
            'total_sold'        => $sold,
            'profit'            => [
                'margin'  => (float) $margin,
                'percent' => round($marginPercent, 2),
            ],
            'seller'            => [
                'id' => $this->seller->id,
                'username' => $this->seller->username,
                'business_name' => $this->seller->business_name,
                'business_logo' => $this->seller->business_logo,
                'best_seller' => $this->seller->is_best_seller,
                'total_followers' => $this->seller->total_followers,
                'rating' => round($this->rating),
            ],
            'reviews' => $reviews,
            'rating' => number_format($reviews->avg('rating') ?? 0, 1),
            'total_reviews' => $reviews->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function groupedOptions(): Attribute
    {
        $options = $this->variants
            ->flatMap(fn($variant) => $variant->option_values)
            ->groupBy(fn($val) => $val->option->id)
            ->map(function ($group) {
                $option = $group->first()->option;

                return [
                    'id'     => $option->id,
                    'name'   => $option->name,
                    'values' => $group->unique('id')->map(fn($v) => [
                        'id'    => $v->id,
                        'value' => $v->value,
                    ])->values()->toArray(),
                ];
            })
            ->values()
            ->toArray();

        return Attribute::make(
            get: fn() => $options
        );
    }

    public function scopeWithDefaultRelations($query)
    {
        return $query->with([
            'brand',
            'images',
            'category',
            'subcategory',
            'variants.option_values',
            'seller',
            'reviews.user',
            'unit'
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->thumbnail ? storage_url($this->thumbnail) : asset('assets/frontend/images/default.png')
        );
    }

    public function availableStock(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->stock_in - $this->stock_out
        );
    }
}
