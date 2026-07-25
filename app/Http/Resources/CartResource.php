<?php

namespace App\Http\Resources;

use App\Domain\Vendor\Http\Resources\SellerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'seller' => SellerResource::make($this->whenLoaded('seller')),
            'items' => CartItemResource::collection($this->whenLoaded('cart_items')),
        ];
    }
}
