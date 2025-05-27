<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'customer_name' => $this->customer_name,
            'customer_email' => $this->customer_email,
            'customer_phone' => $this->customer_phone,
            'customer_address' => $this->customer_address,
            'sub_total' => $this->sub_total,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'shipping_fee' => $this->shipping_fee,
            'total' => $this->total,
            'payable' => $this->payable,
            'due' => $this->due,
            'status' => $this->status,
            'delivery_status'  => $this->delivery_status,
            'created_at' => $this->created_at->format('d m Y h:i A'),
            'seller' => SellerResource::make($this->whenLoaded('seller')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
