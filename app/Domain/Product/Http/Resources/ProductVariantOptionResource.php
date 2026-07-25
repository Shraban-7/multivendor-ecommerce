<?php

namespace App\Domain\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantOptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'option' => $this->option_value->option->name,
            'value' => $this->option_value->value,
        ];
    }
}
