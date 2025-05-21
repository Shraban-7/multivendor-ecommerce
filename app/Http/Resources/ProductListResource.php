<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'thumbnail' => storage_url($this->thumbnail),
            'selling_price' => removeZeroFromDecimal($this->selling_price),
            'discount_type' => $this->discount_type,
            'discount_amount' => $this->discount_amount,
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
            })
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
