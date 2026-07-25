<?php

namespace App\Domain\Shipping\Repositories;

use App\Domain\Shipping\Models\ShippingMethod;

interface ShippingRepositoryInterface
{
    public function findActiveMethodForDistrict(?int $districtId): ?ShippingMethod;
}
