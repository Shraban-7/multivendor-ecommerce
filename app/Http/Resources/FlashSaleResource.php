<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FlashSaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => is_null($this->image) ? null : storage_url($this->image),
            'description' => $this->description,
            'end_time_ms' => time_to_ms($this->end_time),
            'products' => FlashSaleProductResource::collection($this->whenLoaded('products'))
        ];
    }
}
