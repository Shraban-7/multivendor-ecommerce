<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $price           = $this->selling_price;
        $discountedPrice = $this->discounted_price;
        $discount        = null;

        if ($this->discount_amount > 0) {
            $discount = "-{$this->discount_amount}";
            $discount .= $this->discount_type === 'percentage' ? '%' : currency();
        }

        $defaultVariantId = $this->variants
            ->first(fn($v) => ($v->stock_in - $v->stock_out) > 0)?->id;

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'thumbnail'         => storage_url($this->thumbnail),
            'price'             => removeZeroFromDecimal($price),
            'discounted_price'  => removeZeroFromDecimal($discountedPrice),
            'discount'          => $discount,
            'stock'             => ($this->stock_in - $this->stock_out),
            'total_sold'        => number_shorten_format($this->stock_out),
            'avg_rating'        => $this->avg_rating,
            'rating_count'      => number_shorten_format($this->rating_count),
            'short_description' => $this->short_description,
            'description'       => $this->description,
            'category'          => CategoryResource::make($this->whenLoaded('category')),
            'subcategory'       => CategoryResource::make($this->whenLoaded('subcategory')),

            'images'            => $this->whenLoaded('images', function () {
                return $this->imageToArray($this->images);
            }),

            'options'           => collect($this->variants)
                ->flatMap(fn($variant) => $variant->optionValues)
                ->groupBy(fn($val) => $val->option->id)
                ->map(fn($group) => OptionResource::make($group))
                ->values()
                ->toArray(),

            'variants'          => ProductVariantResource::collection(
                $this->variants->map(function ($variant) use ($defaultVariantId) {
                    return tap($variant, fn($v) => $v->default_variant_id = $defaultVariantId);
                })
            ),
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
