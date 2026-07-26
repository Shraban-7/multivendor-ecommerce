<?php

namespace App\Domain\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $price = (float) $this->price;
        $comparePrice = $this->compare_price !== null ? (float) $this->compare_price : null;
        $discount = null;

        if ($comparePrice !== null && $comparePrice < $price && $price > 0) {
            $discount = '-'.round((($price - $comparePrice) / $price) * 100).'%';
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
            'cost_price' => removeZeroFromDecimal($this->cost_price),
            'price' => removeZeroFromDecimal($price),
            'compare_price' => removeZeroFromDecimal($comparePrice),
            'discount' => $discount,
            'image' => $this->image,
            'color_id' => $this->color_id,
            'size_id' => $this->size_id,
            'label' => $this->label,
            'variant_options' => $variantOptions,
        ];
    }
}
