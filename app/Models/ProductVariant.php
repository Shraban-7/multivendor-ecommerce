<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProductVariant extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'option_ids' => 'array',
    ];

    public function scopeWhereProduct($query, Product $product)
    {
        return $query->where('product_id', $product->id);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function option_values()
    {
        return $this->belongsToMany(OptionValue::class, 'product_variant_options', 'product_variant_id', 'option_value_id')
            ->with('option');
    }

    public function options()
    {
        return $this->hasMany(ProductVariantOption::class, 'product_variant_id');
    }

    public function fullName(): Attribute
    {
        $options = [];
        foreach ($this->option_values as $optionValue) {
            $options[] = $optionValue->option->name . ': ' . $optionValue->value;
        }

        return Attribute::make(
            get: fn() => implode(', ', $options)
        );
    }

    public static function generate_sku(): string
    {
        do {
            $sku = strtoupper(Str::random(8));
        } while (self::where('sku', $sku)->exists());

        return $sku;
    }

    public function availableStock(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->stock_in - $this->stock_out
        );
    }

    public function calculatedPrice(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->discounted_price ? $this->discounted_price : $this->selling_price
        );
    }

    public function calculatedDiscount(): Attribute
    {
        return Attribute::make(
            get: function () {
                $discountedPrice = $this->discounted_price ?? 0;
                $sellingPrice = $this->selling_price ?? 0;

                return $sellingPrice - $discountedPrice;
            }
        );
    }

    public function imageUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->image ? storage_url($this->image) : asset('assets/frontend/images/default.png')
        );
    }
}
