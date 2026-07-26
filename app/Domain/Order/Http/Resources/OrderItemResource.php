<?php

namespace App\Domain\Order\Http\Resources;

use App\Domain\Product\Http\Resources\ProductVariantResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $product = $this->product;

        return [
            'id' => $this->id,
            'product_id' => $product->id,
            'name' => $product->name,
            'thumbnail' => $product->imageUrl,
            'quantity' => $this->quantity,
            'cost_price' => money($this->cost_price),
            'price' => money($this->price),
            'unit_price' => money($this->unit_price),
            'discount' => money($this->discount),
            'sub_total' => money($this->sub_total),
            'is_reviewed' => $this->is_reviewed,
            'variant' => ProductVariantResource::make($this->variant),
        ];
    }
}
