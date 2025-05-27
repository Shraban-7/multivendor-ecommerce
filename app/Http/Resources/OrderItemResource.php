<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->product->name,
            'thumbnail' => storage_url($this->product->thumbnail),
            'unit_price' => money($this->unit_price),
            'quantity' => $this->quantity,
            'subtotal' => money($this->sub_total),
        ];
    }
}
