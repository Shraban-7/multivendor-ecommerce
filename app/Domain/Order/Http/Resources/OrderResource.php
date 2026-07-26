<?php

namespace App\Domain\Order\Http\Resources;

use App\Domain\Payment\Models\Payment;
use App\Domain\Vendor\Http\Resources\SellerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $paymentStatus = 0;

        if ($this->due == 0 && ! is_null($this->payment_id) && $this->whenLoaded('payment')?->status == Payment::SUCCESSFUL) {
            $paymentStatus = 1;
        }

        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'sub_total' => money($this->sub_total),
            'discount' => money($this->discount),
            'tax' => money($this->tax),
            'shipping_fee' => money($this->shipping_fee),
            'total' => money($this->total),
            'payable' => money($this->payable),
            'due' => money($this->due),
            'status' => $this->status,
            'payment_status' => $paymentStatus,
            'delivery_status' => $this->delivery_status,
            'created_at' => $this->created_at->format('d m Y h:i A'),
            'seller' => SellerResource::make($this->whenLoaded('seller')),
            'items' => OrderItemResource::collection($this->whenLoaded('items')),
            'billing_address' => $this->whenLoaded('billing_address', function () {
                return [
                    'customer_name' => $this->billing_address->customer_name,
                    'customer_phone' => $this->billing_address->customer_phone,
                    'address' => $this->billing_address->address,
                    'division' => $this->billing_address->division?->name,
                    'district' => $this->billing_address->district?->name,
                ];
            }),
        ];
    }
}
