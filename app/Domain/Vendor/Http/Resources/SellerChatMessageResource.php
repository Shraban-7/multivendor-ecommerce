<?php

namespace App\Domain\Vendor\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerChatMessageResource extends JsonResource
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
            'seller_id' => $this->seller_id,
            'user_id' => $this->user_id,
            'message' => $this->message,
            'is_read' => $this->is_read,
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
