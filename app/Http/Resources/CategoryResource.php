<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            //'category_id' => $this->category_id,
            'name' => $this->name,
            'image' => is_null($this->image) ? null : asset($this->image),
            'subcategories' => CategoryResource::collection($this->whenLoaded('subcategories'))
        ];
    }
}
