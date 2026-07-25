<?php

namespace App\Http\Resources\Seller;

use Illuminate\Http\Resources\Json\JsonResource;

class SellerProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'avatar' => $this->avatar,
            'business_name' => $this->business_name,
            'business_email' => $this->business_email,
            'business_address' => $this->business_address,
            'business_logo' => $this->business_logo,
            'cover_image' => $this->cover_image,
            'is_best_seller' => (bool) $this->is_best_seller,
            'total_sold' => (int) $this->total_sold,
            'total_followers' => (int) ($this->total_followers ?? 0),
            'rating' => (float) ($this->rating ?? 0),
            'rating_count' => (int) ($this->rating_count ?? 0),
            'shipping_cost' => (float) ($this->shipping_cost ?? 0),
            'status' => $this->status,
            'commission_type' => $this->commission_type,
            'commission_amount' => (float) ($this->commission_amount ?? 0),
            'division_id' => $this->division_id,
            'district_id' => $this->district_id,
            'created_at' => $this->created_at,
        ];
    }
}
