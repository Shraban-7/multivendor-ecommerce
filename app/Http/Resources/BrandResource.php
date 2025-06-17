<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' =>is_null($this->image) ? asset('assets/frontend/images/placeholder-img.jpg') : storage_url($this->image),
        ];
    }
}
