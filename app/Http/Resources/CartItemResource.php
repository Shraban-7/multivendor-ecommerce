<?php

namespace App\Http\Resources;

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

        $price = $variant ? $variant->selling_price : $product->selling_price;
        $discountedPrice = $variant ? $variant->discounted_price : $product->discounted_price;

        $discount = null;
        if ($product->discount_amount > 0) {
            $discount = "-{$product->discount_amount}";
            $discount .= $product->discount_type == 'percentage' ? '%' : currency();
        }

        return [
            'id' => $this->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'thumbnail' => $product->imageUrl,
            'quantity' => $this->quantity,
            'price' => removeZeroFromDecimal($price),
            'discounted_price' => removeZeroFromDecimal($discountedPrice),
            'discount' => $discount,
            'category' => CategoryResource::make($product->category),
            'subcategory' => CategoryResource::make($product->subcategory),
            'variant' => ProductVariantResource::make($this->variant),
        ];
    }
}
