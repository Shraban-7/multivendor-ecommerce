<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $price = $this->selling_price;
        $discountedPrice = $this->discounted_price;
        $discount = null;

        if ($this->discount_amount > 0) {

            $discount = "-" . removeZeroFromDecimal($this->discount_amount);
            $discount .= $this->discount_type === 'percentage' ? '%' : currency();
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumbnail' => storage_url($this->thumbnail),
            'price' => removeZeroFromDecimal($price),
            'discounted_price' => removeZeroFromDecimal($discountedPrice),
            'discount' => $discount,
            'stock' => ($this->stock_in - $this->stock_out),
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

            'available_options' => [],
            'variants' => [],
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
