<?php

namespace App\Domain\Shipping\Repositories;

use App\Domain\Shipping\Models\ShippingMethod;

class EloquentShippingRepository implements ShippingRepositoryInterface
{
    public function findActiveMethodForDistrict(?int $districtId): ?ShippingMethod
    {
        return ShippingMethod::query()
            ->where('is_active', true)
            ->when($districtId, fn ($q) => $q->where(function ($q) use ($districtId) {
                $q->whereNull('district_id')->orWhere('district_id', $districtId);
            }))
            ->orderByRaw('district_id is null')
            ->first();
    }
}
