<?php

namespace App\Domain\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'image' => storage_url($this->image),
            'button_text' => $this->button_text,
            'button_link' => $this->button_link,
            'link' => $this->button_link,
            'section' => $this->section,
            'sort_order' => $this->sort_order,
        ];
    }
}
