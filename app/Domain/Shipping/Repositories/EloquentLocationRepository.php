<?php

namespace App\Domain\Shipping\Repositories;

use App\Domain\Shipping\Models\District;
use App\Domain\Shipping\Models\Division;
use App\Domain\Shipping\Models\Union;
use App\Domain\Shipping\Models\Upazila;
use Illuminate\Support\Collection;

class EloquentLocationRepository implements LocationRepositoryInterface
{
    public function getAllDivisions(): Collection
    {
        return Division::orderBy('name', 'ASC')->get();
    }

    public function getDistrictsByDivisionId(int $divisionId): Collection
    {
        return District::where('division_id', $divisionId)
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function getUpazilasByDistrictId(int $districtId): Collection
    {
        return Upazila::where('district_id', $districtId)
            ->orderBy('name', 'ASC')
            ->get();
    }

    public function getUnionsByUpazilaId(int $upazilaId): Collection
    {
        return Union::where('upazila_id', $upazilaId)
            ->orderBy('name', 'ASC')
            ->get();
    }
}
