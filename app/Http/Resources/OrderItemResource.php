<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'order_id'              => $this->order_id,
            'product_id'            => $this->product_id,
            'product_variant_ids'   => $this->product_variant_ids,
            'product_variant_price' => $this->product_variant_price,
            'buying_price'          => $this->buying_price,
            'unit_price'            => $this->unit_price,
            'quantity'              => $this->quantity,
            'discount'              => $this->discount,
            'sub_total'             => $this->sub_total,

            'product'               => $this->whenLoaded('product', fn() => [
                'id'                   => $this->product->id,
                'category_id'          => $this->product->category_id,
                'subcategory_id'       => $this->product->subcategory_id,
                'brand_id'             => $this->product->brand_id,
                'seller_id'            => $this->product->seller_id,
                'name'                 => $this->product->name,
                'slug'                 => $this->product->slug,
                'thumbnail'            => $this->product->thumbnail,
                'short_description'    => $this->product->short_description,
                'description'          => $this->product->description,
                'buying_price'         => $this->product->buying_price,
                'selling_price'        => $this->product->selling_price,
                'discount_type'        => $this->product->discount_type,
                'discount_amount'      => $this->product->discount_amount,
                'unit_id'              => $this->product->unit_id,
                'unit_value'           => $this->product->unit_value,
                'sku'                  => $this->product->sku,
                'barcode'              => $this->product->barcode,
                'is_trending'          => $this->product->is_trending,
                'best_selling'         => $this->product->best_selling,
                'is_featured'          => $this->product->is_featured,
                'is_interest'          => $this->product->is_interest,
                'is_community'         => $this->product->is_community,
                'is_lightdeal'         => $this->product->is_lightdeal,
                'lightdeal_expired_at' => $this->product->lightdeal_expired_at,
                'video'                => $this->product->video,
                'is_active'            => $this->product->is_active,
                'stock_in'             => $this->product->stock_in,
                'stock_out'            => $this->product->stock_out,
                'shipping_cost'        => $this->product->shipping_cost,
                'tax'                  => $this->product->tax,
                'views'                => $this->product->views,
                'avg_rating'           => $this->product->avg_rating,
                'rating_count'         => $this->product->rating_count,
            ]),
        ];

    }
}
