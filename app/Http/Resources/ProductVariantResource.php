<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    protected $defaultVariantId;

    public function __construct($resource, $defaultVariantId = null)
    {
        parent::__construct($resource);
        $this->defaultVariantId = $defaultVariantId;
    }

    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'sku'              => $this->sku,
            'stock'            => $this->stock_in - $this->stock_out,
            'price'            => (string) $this->selling_price,
            'discounted_price' => (string) $this->discounted_price,
            'image'            => $this->image,
            'value_ids'        => $this->optionValues->pluck('id')->sort()->values()->toArray(),
            'default'          => $this->id === $this->defaultVariantId,
        ];
    }
}
