<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->product;

        $price = $product->selling_price;
        $discountedPrice = $product->discounted_price;
        $discount = null;
        if ($product->discount_amount > 0) {
            $discount = "-{$product->discount_amount}";
            $discount .= $product->discount_type == 'percentage' ? '%' : currency();
        }

        return [
            'id' => $this->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'thumbnail' => storage_url($product->thumbnail),
            'quantity' => $this->quantity,
            'price' => removeZeroFromDecimal($price),
            'discounted_price' => removeZeroFromDecimal($discountedPrice),
            'discount' => $discount,
        ];
    }
}
