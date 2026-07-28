<?php

namespace App\Domain\Shipping\Services;

use App\Domain\Shipping\Repositories\LocationRepositoryInterface;
use App\Domain\Shipping\Repositories\ShippingRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ShippingService
{
    public function __construct(
        protected LocationRepositoryInterface $locationRepository,
        protected ShippingRepositoryInterface $shippingRepository
    ) {}

    public function divisions(): Collection
    {
        return Cache::remember('shipping.divisions', 3600, fn () => $this->locationRepository->getAllDivisions());
    }

    public function districts(?int $divisionId = null): Collection
    {
        if ($divisionId) {
            return $this->locationRepository->getDistrictsByDivisionId($divisionId);
        }

        return $this->locationRepository->getAllDistricts();
    }

    public function calculateCharge(?int $districtId, float $orderTotal = 0): float
    {
        $method = $this->shippingRepository->findActiveMethodForDistrict($districtId);

        if (! $method) {
            return 0.0;
        }

        if ($method->free_above && $orderTotal >= (float) $method->free_above) {
            return 0.0;
        }

        return (float) $method->charge;
    }
}
