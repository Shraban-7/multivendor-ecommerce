<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'image' => $this->image ? storage_url($this->image) : null,
            'email' => $this->email,
            'secondary_email' => $this->secondary_email,
            'phone' => $this->phone,
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'zip' => $this->zip,
        ];
    }
}
