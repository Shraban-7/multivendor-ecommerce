<?php

namespace App\Domain\Shipping\Http\Controllers\Api;

use App\Domain\Shipping\Http\Resources\DistrictResource;
use App\Domain\Shipping\Http\Resources\DivisionResource;
use App\Domain\Shipping\Repositories\LocationRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(protected LocationRepositoryInterface $locationRepository) {}

    public function divisions()
    {
        $divisions = $this->locationRepository->getAllDivisions();

        return apiResourceResponse(DivisionResource::collection($divisions));
    }

    public function districts(Request $request)
    {
        $validator = validateRequest($request, [
            'division_id' => 'required|exists:divisions,id',
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first());
        }

        $districts = $this->locationRepository->getDistrictsByDivisionId((int) $request->division_id);

        return apiResourceResponse(DistrictResource::collection($districts));
    }
}
