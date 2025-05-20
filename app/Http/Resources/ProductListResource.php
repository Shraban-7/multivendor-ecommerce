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
        ];
    }
}
