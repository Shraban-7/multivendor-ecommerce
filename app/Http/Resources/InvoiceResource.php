<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'date' => $this->created_at->format('Y-m-d'),
            'logo' => storage_url($this->seller->business_logo),
            'items' => $this->items->map(function ($item) {
                return [
                    'product_name' => $item->product->name,
                    'unit_price' => money($item->unit_price),
                    'quantity' => $item->quantity,
                    'sub_total' => money($item->sub_total),
                ];
            }),
            'total' => money($this->total),
            'discount' => money($this->discount),
            'payable' => money($this->payable),
            'due' => money($this->due),
        ];
    }
}
