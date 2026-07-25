<?php

namespace App\Domain\Shipping\Repositories;

use App\Domain\Shipping\Models\District;
use App\Domain\Shipping\Models\Division;
use App\Domain\Shipping\Models\Union;
use App\Domain\Shipping\Models\Upazila;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class EloquentLocationRepository implements LocationRepositoryInterface
{
    public function getAllDivisions(): Collection
    {
        return Cache::remember('divisions:all', 86400, function () {
            return Division::orderBy('name', 'ASC')->get();
        });
    }

    public function getDistrictsByDivisionId(int $divisionId): Collection
    {
        return Cache::remember("districts:division:$divisionId", 86400, function () use ($divisionId) {
            return District::where('division_id', $divisionId)
                ->orderBy('name', 'ASC')
                ->get();
        });
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
