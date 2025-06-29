<?php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'invoice_id'       => $this->invoice_id,
            'date' => $this->created_at->format('Y-m-d'),
            'logo'             => storage_url($this->seller->business_logo),
            'customer_name'    => $this->customer_name ?? null,
            'customer_address' => $this->customer_address ?? null,
            'customer_phone'   => $this->customer_phone ?? null,
            'items'            => $this->items->map(function ($item) {
                return [
                    'product_name' => $item->product->name,
                    'unit_price'   => money($item->unit_price),
                    'quantity'     => $item->quantity,
                    'sub_total'    => money($item->sub_total),
                ];
            }),
            'total' => money($this->total),
            'discount' => money($this->discount),
            'payable' => money($this->payable),
            'due' => money($this->due),
        ];
    }
}
