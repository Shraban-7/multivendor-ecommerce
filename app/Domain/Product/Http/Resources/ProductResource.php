<?php

namespace App\Domain\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $price = (float) $this->price;
        $comparePrice = $this->compare_price !== null ? (float) $this->compare_price : null;
        $discount = null;

        if ($comparePrice !== null && $comparePrice < $price && $price > 0) {
            $discount = '-'.round((($price - $comparePrice) / $price) * 100).'%';
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumbnail' => $this->imageUrl,
            'cost_price' => removeZeroFromDecimal($this->cost_price),
            'price' => removeZeroFromDecimal($price),
            'compare_price' => removeZeroFromDecimal($comparePrice),
            'discount' => $discount,
            'stock' => ($this->stock_in - $this->stock_out),
            'low_stock' => ($this->stock_in - $this->stock_out) <= $this->low_stock_quantity ? true : false,
            'total_sold' => number_shorten_format($this->stock_out),
            'avg_rating' => $this->avg_rating,
            'rating_count' => number_shorten_format($this->rating_count),
            'short_description' => $this->short_description,
            'description' => $this->description,
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'subcategory' => CategoryResource::make($this->whenLoaded('subcategory')),

            'images' => $this->whenLoaded('images', function () {
                return $this->imageToArray($this->images);
            }),

            'available_options' => $this->grouped_options,
            'variants' => ProductVariantResource::collection($this->whenLoaded('variants')),
        ];
    }

    private function imageToArray($images): array
    {
        $imgArray = [];
        foreach ($images as $img) {
            $imgArray[] = storage_url($img->image);
        }

        return $imgArray;
    }
}
