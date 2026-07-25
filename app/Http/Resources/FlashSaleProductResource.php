<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlashSaleProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $percentageSold = $this->stock_in > 0 ? $this->stock_out / $this->stock_in : 0;

        return [
            'id' => $this->id,
            'stock_in' => (string) $this->stock_in,
            'stock_out' => (string) $this->stock_out,
            'percentage_sold' => round($percentageSold, 4),
            'product' => ProductListResource::make($this->product),
        ];
    }
}
