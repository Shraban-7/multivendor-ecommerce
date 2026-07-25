<?php

namespace App\Http\Controllers\Api;

use App\Domain\Payment\Models\PaymentGateway;
use App\Domain\Shipping\Repositories\LocationRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\DistrictResource;
use App\Http\Resources\DivisionResource;
use App\Http\Resources\PaymentGatewayResource;
use Illuminate\Http\Request;

class DataController extends Controller
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

    public function paymentGateways()
    {
        $paymentGateways = PaymentGateway::where('is_enabled', true)->get();

        return apiResourceResponse(PaymentGatewayResource::collection($paymentGateways));
    }
}
