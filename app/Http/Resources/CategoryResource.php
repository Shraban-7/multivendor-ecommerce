<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $default = asset('assets/frontend/images/placeholder-img.jpg');
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            //'icon' => '0xe670',
            'icon' => is_null($this->app_icon) ? $default : storage_url($this->app_icon),
            'image' => is_null($this->image) ? $default : storage_url($this->image),
            'subcategories' => CategoryResource::collection($this->whenLoaded('subcategories'))
        ];
    }
}
