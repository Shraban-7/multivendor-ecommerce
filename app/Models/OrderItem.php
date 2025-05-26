<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant');
    }

    public function getVariantOptionAttribute()
    {
        if (! $this->product_variant_ids) {
            return collect();
        }

        $variantIds = is_array($this->product_variant_ids)
        ? $this->product_variant_ids
        : json_decode($this->product_variant_ids, true);

        $variantIds = array_map('intval', array_filter($variantIds));

        return ProductVariant::with(['option.product_attribute'])
            ->whereIn('id', $variantIds)
            ->get()
            ->map(function ($variant) {
                return [
                    'productAttribute' => $variant->option->product_attribute->name ?? null,
                    'option'           => $variant->option->value ?? null,
                ];
            });
    }
}
