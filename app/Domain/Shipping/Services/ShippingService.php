<?php

namespace App\Domain\Shipping\Services;

use App\Domain\Shipping\Models\District;
use App\Domain\Shipping\Models\Division;
use App\Domain\Shipping\Models\ShippingMethod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ShippingService
{
    public function divisions(): Collection
    {
        return Cache::remember('shipping.divisions', 3600, fn () => Division::with('districts')->orderBy('name')->get());
    }

    public function districts(?int $divisionId = null): Collection
    {
        $query = District::query()->orderBy('name');
        if ($divisionId) {
            $query->where('division_id', $divisionId);
        }

        return $query->get();
    }

    public function calculateCharge(?int $districtId, float $orderTotal = 0): float
    {
        $method = ShippingMethod::query()
            ->where('is_active', true)
            ->when($districtId, fn ($q) => $q->where(function ($q) use ($districtId) {
                $q->whereNull('district_id')->orWhere('district_id', $districtId);
            }))
            ->orderByRaw('district_id is null')
            ->first();

        if (! $method) {
            return 0.0;
        }

        if ($method->free_above && $orderTotal >= (float) $method->free_above) {
            return 0.0;
        }

        return (float) $method->charge;
    }
}
