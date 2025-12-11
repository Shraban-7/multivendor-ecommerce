<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlashSaleProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'stock_in' => $this->stock_in,
            'stock_out' => $this->stock_out,
            'product' => ProductListResource::make($this->product)
        ];
    }
}
