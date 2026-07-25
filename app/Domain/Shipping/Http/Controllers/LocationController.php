<?php

namespace App\Domain\Shipping\Http\Controllers;

use App\Domain\Shipping\Repositories\LocationRepositoryInterface;
use App\Http\Controllers\Controller;

class LocationController extends Controller
{
    public function __construct(protected LocationRepositoryInterface $locationRepository) {}

    public function getDistricts($divisionId)
    {
        $districts = $this->locationRepository->getDistrictsByDivisionId((int) $divisionId)
            ->map(fn ($district) => [
                'id' => $district->id,
                'name' => $district->name,
            ]);

        return response()->json($districts);
    }
}
