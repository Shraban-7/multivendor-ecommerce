<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentGatewayResource extends JsonResource
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
            'name' => $this->name,
            'image' => storage_url($this->image),
            'payment_url' => $this->payment_url,
            'credentials' => $this->credentials,
            'is_enable' => $this->is_enabled,
            'is_default' => $this->is_default
        ];
    }
}
