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
            'cover_image' => $this->cover_image ? storage_url($this->cover_image) : asset('assets/frontend/images/default.png'),
            'email' => $this->business_email,
            'address' => $this->business_address,
            'is_best_seller' => (bool) $this->is_best_seller,
            'image' => storage_url($this->business_logo),
            'products_count' => (string) $this->total_items,
            'sales_count' => (string) $this->total_items,
            'followers_count' => (string) $this->total_followers,
            'description' => $this->business_description ?? 'This seller has not provided a business description yet.',
            'join_date' => $this->created_at->format('M d, Y'),
            'location' => trim(
                collect([
                    optional($this->district)->name,
                    optional($this->division)->name,
                ])->filter()->implode(', ')
            ),
        ];
    }
}
