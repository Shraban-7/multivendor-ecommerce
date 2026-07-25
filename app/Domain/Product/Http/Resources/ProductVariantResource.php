<?php

namespace App\Domain\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $price = $this->selling_price;
        $discountedPrice = $this->discounted_price;
        $discount = null;

        if ($this->discount_amount > 0) {
            $discount = "-{$this->discount_amount}";
            $discount .= $this->discount_type === 'percentage' ? '%' : currency();
        }

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'stock' => $this->stock_in - $this->stock_out,
            'price' => removeZeroFromDecimal($price),
            'discounted_price' => removeZeroFromDecimal($discountedPrice),
            'discount' => $discount,
            'image' => $this->image,
            'value_ids' => $this->option_values->pluck('id')->sort()->values()->toArray(),
            'default' => $this->is_default,
            'variant_options' => ProductVariantOptionResource::collection($this->options),
        ];
    }
}
