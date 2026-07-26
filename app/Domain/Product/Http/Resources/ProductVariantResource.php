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

        $variantOptions = [];
        if ($this->relationLoaded('color') && $this->color) {
            $variantOptions[] = [
                'option' => 'Color',
                'value' => $this->color->name,
            ];
        }
        if ($this->relationLoaded('size') && $this->size) {
            $variantOptions[] = [
                'option' => 'Size',
                'value' => $this->size->name,
            ];
        }

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'stock' => $this->stock_in - $this->stock_out,
            'price' => removeZeroFromDecimal($price),
            'discounted_price' => removeZeroFromDecimal($discountedPrice),
            'discount' => $discount,
            'image' => $this->image,
            'color_id' => $this->color_id,
            'size_id' => $this->size_id,
            'label' => $this->label,
            'default' => $this->is_default,
            'variant_options' => $variantOptions,
        ];
    }
}
