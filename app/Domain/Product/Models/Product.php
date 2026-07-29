<?php

namespace App\Domain\Product\Models;

use App\Domain\Order\Models\OrderItem;
use App\Domain\Review\Models\Review;
use App\Domain\Vendor\Models\Seller;
use App\Enums\PaymentType;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected static function newFactory(): Factory
    {
        return ProductFactory::new();
    }

    protected $casts = [
        'payment_type' => PaymentType::class,
        'cost_price' => 'decimal:2',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'specifications' => 'array',
        'is_visible' => 'boolean',
    ];

    const STATUS_PENDING_APPROVAL = 0;

    const STATUS_ACTIVE = 1;

    const STATUS_INACTIVE = 2;

    const STATUS_DELETED = 3;
    const STATUS_DRAFT = 4;

    /**
     * Allow /api/products/{product} to resolve by id or slug.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field !== null) {
            return $this->where($field, $value)->firstOrFail();
        }

        return $this->where(function ($query) use ($value) {
            $query->where('id', $value)->orWhere('slug', $value);
        })->firstOrFail();
    }

    public function statusName(): Attribute
    {
        $statusName = match ($this->status) {
            self::STATUS_PENDING_APPROVAL => 'Pending Approval',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_INACTIVE => 'Inactive',
            self::STATUS_DELETED => 'Deleted',
            self::STATUS_DRAFT => 'Draft',
            default => null,
        };

        return Attribute::make(
            get: fn () => $statusName
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

    public function stock_history()
    {
        return $this->hasMany(StockHistory::class, 'product_id');
    }

    public function productStocks()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function seo(): HasOne
    {
        return $this->hasOne(ProductSeo::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tag');
    }

    public function toDetailsArray()
    {
        $margin = $this->price - $this->cost_price;
        $marginPercent = $this->cost_price > 0 ? ($margin / $this->cost_price) * 100 : 0;

        $sold = $this->variants->sum('stock_out');

        $reviews = $this->reviews;

        $images = collect([$this->thumbnail])
            ->merge($this->images->pluck('image'))
            ->merge($this->variants->pluck('image'))
            ->filter()
            ->unique()
            ->values()
            ->map(fn ($path) => storage_url($path))
            ->filter()
            ->values()
            ->all();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'category_id' => $this->category?->id,
            'category' => $this->category?->name,
            'subcategory' => $this->subcategory?->name,
            'brand' => $this->brand?->name,
            'name' => $this->name,
            'thumbnail' => $this->imageUrl,
            'images' => $images,
            'slider' => $images,

            'short_description' => $this->short_description,
            'description' => $this->description,
            'price' => $this->price,
            'compare_price' => $this->compare_price,
            'cost_price' => $this->cost_price,
            'stock_status' => $this->stock_status,
            'in_stock' => $this->stock_in,
            'sold_out' => $this->stock_out,
            'stock' => $this->stock_in - $this->stock_out,
            'almost_sold_out' => ($this->stock_in - $this->stock_out) <= $this->low_stock_quantity ? true : false,
            'variants' => $this->variants->where('status', true)->map(function ($variant) {
                return [
                    'id' => $variant->id,
                    'sku' => $variant->sku,
                    'stock' => $variant->stock_in - $variant->stock_out,
                    'price' => $variant->price,
                    'compare_price' => $variant->compare_price,
                    'cost_price' => $variant->cost_price,
                    'image' => $variant->image ? storage_url($variant->image) : null,
                    'color_id' => $variant->color_id,
                    'size_id' => $variant->size_id,
                ];
            })->values()->all(),
            'options' => $this->grouped_options,
            'total_sold' => $sold,
            'profit' => [
                'margin' => (float) $margin,
                'percent' => round($marginPercent, 2),
            ],
            'seller' => $this->seller ? [
                'id' => $this->seller->id,
                'username' => $this->seller->username,
                'business_name' => $this->seller->business_name,
                'business_logo' => $this->seller->business_logo,
                'best_seller' => $this->seller->is_best_seller,
                'total_followers' => $this->seller->total_followers,
                'rating' => round($this->avg_rating),
            ] : null,
            'reviews' => $reviews,
            'rating' => number_format($this->avg_rating ?? 0, 1),
            'total_reviews' => $reviews->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function groupedOptions(): Attribute
    {
        $this->loadMissing('variants.color', 'variants.size');

        $colors = $this->variants
            ->filter(fn ($v) => $v->relationLoaded('color') && $v->getRelation('color'))
            ->unique('color_id')
            ->values()
            ->map(fn ($v) => [
                'id' => $v->getRelation('color')->id,
                'value' => $v->getRelation('color')->name,
                'hex' => $v->getRelation('color')->hex_code,
                'image' => $v->getRelation('color')->image,
            ]);

        $sizes = $this->variants
            ->filter(fn ($v) => $v->relationLoaded('size') && $v->getRelation('size'))
            ->unique('size_id')
            ->values()
            ->sortBy(fn ($v) => $v->getRelation('size')->sort_order)
            ->values()
            ->map(fn ($v) => [
                'id' => $v->getRelation('size')->id,
                'value' => $v->getRelation('size')->name,
            ]);

        $options = [];
        if ($colors->isNotEmpty()) {
            $options[] = ['id' => 'color', 'name' => 'Color', 'values' => $colors->values()->all()];
        }
        if ($sizes->isNotEmpty()) {
            $options[] = ['id' => 'size', 'name' => 'Size', 'values' => $sizes->values()->all()];
        }

        return Attribute::make(
            get: fn () => $options
        );
    }

    public function scopeWithDefaultRelations($query)
    {
        return $query->with([
            'brand',
            'images',
            'category',
            'subcategory',
            'variants.color', 'variants.size',
            'seller',
            'reviews.user',
            'unit',
        ]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->thumbnail ? storage_url($this->thumbnail) : asset('assets/frontend/images/default.png')
        );
    }

    public function availableStock(): Attribute
    {
        return Attribute::make(
            get: fn () => (int) $this->stock_in - (int) $this->stock_out
        );
    }

    public function totalStock(): Attribute
    {
        return Attribute::make(
            get: function () {
                $total = (int) $this->availableStock;

                foreach ($this->variants as $variant) {
                    $total += (int) $variant->availableStock;
                }

                return $total;
            }
        );
    }

    public function recalculateRating(): void
    {
        $this->avg_rating = round((float) $this->reviews()->approved()->avg('rating') ?? 0, 1);
        $this->rating_count = $this->reviews()->approved()->count();
        $this->save();
    }

    public static function generateSku(int $sellerId): string
    {
        return DB::transaction(function () use ($sellerId) {

            $seller = DB::table('sellers')
                ->where('id', $sellerId)
                ->lockForUpdate()
                ->first();

            $next = (int) $seller->sku_counter + 1;

            DB::table('sellers')
                ->where('id', $sellerId)
                ->update(['sku_counter' => $next]);

            $numericLength = 8 - strlen($seller->code);
            if ($numericLength <= 0) {
                throw new \Exception('Seller code too long for 8-char SKU');
            }

            $sku = $seller->code.str_pad($next, $numericLength, '0', STR_PAD_LEFT);

            return $sku;
        });
    }

    /**
     * Generate a unique 12-digit numeric barcode (CODE-128-compatible).
     * Used as default for `barcode` when none is provided.
     */
    public static function generateBarcode(): string
    {
        for ($i = 0; $i < 12; $i++) {
            // 12-digit numeric range with leading prefix 200 (marketing-purpose range)
            $candidate = '200' . mt_rand(0, 999999999);
            $candidate = str_pad($candidate, 12, '0', STR_PAD_LEFT);
            // Append EAN-13 check digit
            $candidate .= static::eanCheckDigit($candidate);

            $existsOnProducts = DB::table('products')->where('barcode', $candidate)->exists();
            $existsOnVariants = DB::table('product_variants')->where('barcode', $candidate)->exists();

            if (! $existsOnProducts && ! $existsOnVariants) {
                return $candidate;
            }
        }

        // Fallback: timestamp-based unique numeric string
        return '200' . substr((string) (microtime(true) * 1000), -8) . mt_rand(10, 99);
    }

    /**
     * EAN-13 check digit (mod-10) for nicer hardware compatibility.
     */
    public static function eanCheckDigit(string $twelveDigits): int
    {
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $digit = (int) $twelveDigits[$i];
            $sum += ($i % 2 === 0) ? $digit : $digit * 3;
        }
        return (10 - ($sum % 10)) % 10;
    }

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->barcode)) {
                $product->barcode = static::generateBarcode();
            }
        });

        static::updating(function (Product $product) {
            if (empty($product->barcode)) {
                $product->barcode = static::generateBarcode();
            }
        });
    }
}

