<?php

namespace App\Domain\Product\Models;

use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ProductVariant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /** Always needed for labels / storefront display; avoids lazy-load violations. */
    protected $with = ['color', 'size'];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeWhereProduct($query, Product $product)
    {
        return $query->where('product_id', $product->id);
    }

    public function scopeForSeller($query, Seller $seller)
    {
        return $query->whereHas('product', fn ($q) => $q->where('seller_id', $seller->id));
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class, 'product_variant_options', 'product_variant_id', 'option_value_id');
    }

    public function variantImages(): HasMany
    {
        return $this->hasMany(ProductVariantImage::class, 'product_variant_id');
    }

    public function primaryImage(): HasMany
    {
        return $this->hasMany(ProductVariantImage::class, 'product_variant_id')->where('is_primary', true);
    }

    public function stockHistories(): HasMany
    {
        return $this->hasMany(StockHistory::class, 'product_variant_id');
    }

    public function label(): Attribute
    {
        return Attribute::make(
            get: function () {
                $optionLabels = $this->relationLoaded('optionValues')
                    ? $this->optionValues->map(fn ($ov) => $ov->value)->filter()->values()->toArray()
                    : [];

                $parts = array_filter([
                    $this->relationLoaded('color') ? $this->getRelation('color')?->name : null,
                    $this->relationLoaded('size') ? $this->getRelation('size')?->name : null,
                    ...$optionLabels,
                ]);

                return implode(' / ', $parts) ?: 'Default';
            }
        );
    }

    public function availableStock(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->stock_in - $this->stock_out
        );
    }

    public function calculatedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->compare_price ?? $this->price
        );
    }

    public function discountPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->compare_price ?? $this->price
        );
    }

    public function calculatedDiscount(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->compare_price === null) {
                    return 0;
                }

                return max(0, (float) $this->price - (float) $this->compare_price);
            }
        );
    }

    public function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image
                ? storage_url($this->image)
                : ($this->relationLoaded('variantImages') && $this->variantImages->isNotEmpty()
                    ? storage_url($this->variantImages->first()->image_path)
                    : asset('assets/frontend/images/default.png'))
        );
    }

    /**
     * Generate a unique 12-digit numeric barcode for variants.
     */
    public static function generateBarcode(): string
    {
        for ($i = 0; $i < 12; $i++) {
            $candidate = '200' . mt_rand(0, 999999999);
            $candidate = str_pad($candidate, 12, '0', STR_PAD_LEFT);
            $candidate .= Product::eanCheckDigit($candidate);

            $existsOnProducts = DB::table('products')->where('barcode', $candidate)->exists();
            $existsOnVariants = DB::table('product_variants')->where('barcode', $candidate)->exists();

            if (! $existsOnProducts && ! $existsOnVariants) {
                return $candidate;
            }
        }

        return '200' . substr((string) (microtime(true) * 1000), -8) . mt_rand(10, 99);
    }

    protected static function booted(): void
    {
        static::creating(function (ProductVariant $variant) {
            if (empty($variant->barcode)) {
                $variant->barcode = static::generateBarcode();
            }
        });

        static::updating(function (ProductVariant $variant) {
            // Preserve barcode unless explicitly cleared.
        });
    }
}
