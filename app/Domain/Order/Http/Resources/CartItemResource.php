<?php

namespace App\Domain\Order\Http\Resources;

use App\Domain\Product\Http\Resources\CategoryResource;
use App\Domain\Product\Http\Resources\ProductVariantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->product;
        $variant = $this->variant;

        $price = (float) ($variant ? $variant->price : $product->price);
        $comparePrice = $variant
            ? ($variant->compare_price !== null ? (float) $variant->compare_price : null)
            : ($product->compare_price !== null ? (float) $product->compare_price : null);

        $discount = null;
        if ($comparePrice !== null && $comparePrice < $price && $price > 0) {
            $discount = '-'.round((($price - $comparePrice) / $price) * 100).'%';
        }

        return [
            'id' => $this->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'thumbnail' => $product->imageUrl,
            'quantity' => $this->quantity,
            'price' => removeZeroFromDecimal($price),
            'compare_price' => removeZeroFromDecimal($comparePrice),
            'discount' => $discount,
            'category' => CategoryResource::make($product->category),
            'subcategory' => CategoryResource::make($product->subcategory),
            'variant' => ProductVariantResource::make($this->variant),
        ];
    }
}
