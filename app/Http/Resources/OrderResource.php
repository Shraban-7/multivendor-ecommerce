<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $paymentStatus = 1;

        if (is_null($this->payment_id) && $this->due > 0) {
            $paymentStatus = 0;
        }

        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'customer_name' => $this->customer_name ?? '',
            'customer_email' => $this->customer_email ?? '',
            'customer_phone' => $this->customer_phone ?? '',
            'customer_address' => $this->customer_address ?? '',
            'sub_total' => money($this->sub_total),
            'discount' => money($this->discount),
            'tax' => money($this->tax),
            'shipping_fee' => money($this->shipping_fee),
            'total' => money($this->total),
            'payable' => money($this->payable),
            'due' => money($this->due),
            'status' => $this->status,
            'payment_status' => $paymentStatus,
            'delivery_status'  => $this->delivery_status,
            'created_at' => $this->created_at->format('d m Y h:i A'),
            'seller' => SellerResource::make($this->whenLoaded('seller')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
