<?php

namespace App\Domain\Shipping\Repositories;

use Illuminate\Support\Collection;

interface LocationRepositoryInterface
{
    public function getAllDivisions(): Collection;

    public function getDistrictsByDivisionId(int $divisionId): Collection;

    public function getUpazilasByDistrictId(int $districtId): Collection;

    public function getUnionsByUpazilaId(int $upazilaId): Collection;
}
