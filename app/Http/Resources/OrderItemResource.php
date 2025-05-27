<?php

namespace App\Http\Resources;

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
            'thumbnail' => storage_url($product->thumbnail),
            'quantity' => $this->quantity,
            'price' => money($this->unit_price),
            'discount' => money($this->discount),
            'discounted_price' => money($this->unit_price - $this->discount),
        ];
    }
}
