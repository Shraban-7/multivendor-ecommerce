<?php

namespace App\Http\Resources;

use App\Domain\Shipping\Http\Resources\DistrictResource;
use App\Domain\Shipping\Http\Resources\DivisionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BillingAddressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone,
            'address' => $this->address,
            'type' => $this->type,
            'is_default' => $this->is_default,
            'division' => DivisionResource::make($this->division),
            'district' => DistrictResource::make($this->district),
        ];
    }
}
