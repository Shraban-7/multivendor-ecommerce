<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->business_name,
            'email' => $this->business_email,
            'address' => $this->business_address,
            'image' => storage_url($this->business_logo),
            'products_count' => $this->total_items,
            'sales_count' => $this->total_items,
            'followers_count' => $this->total_items,
        ];
    }
}
