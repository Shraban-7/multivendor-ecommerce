<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlashSaleProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $percentageSold = $this->stock_in > 0 ? ($this->stock_out / $this->stock_in) * 100 : 0;
        
        return [
            'id' => $this->id,
            'stock_in' => $this->stock_in,
            'stock_out' => $this->stock_out,
            'percentage_sold' => round($percentageSold, 2),
            'product' => ProductListResource::make($this->product)
        ];
    }
}
