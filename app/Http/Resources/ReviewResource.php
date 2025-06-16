<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_name' => $this->user->name,
            'user_image' => $this->user->avatar,
            'rating' => $this->rating,
            'review_text' => $this->review_text,
            'review_images' => $this->whenLoaded('images', function () {
                return $this->imageToArray($this->images);
            }),
        ];
    }

    private function imageToArray($images): array
    {
        $imgArray = [];
        foreach ($images as $img) {
            $imgArray[] = storage_url($img->image);
        }

        return $imgArray;
    }
}
