<?php

namespace App\Http\Controllers\Api;

use App\Models\District;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DistrictResource;
use App\Http\Resources\DivisionResource;
use App\Http\Resources\PaymentGatewayResource;
use App\Models\PaymentGateway;

class DataController extends Controller
{
    public function divisions()
    {
        $divisions = Division::orderBy('name', 'ASC')->get();

        return apiResourceResponse(DivisionResource::collection($divisions));
    }

    public function districts(Request $request)
    {
        $validator = validateRequest($request, [
            'division_id' => 'required|exists:divisions,id'
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first());
        }

        $districts = District::where('division_id', $request->division_id)->orderBy('name', 'ASC')->get();

        return apiResourceResponse(DistrictResource::collection($districts));
    }

    public function paymentGateways()
    {
        $paymentGateways = PaymentGateway::all();

        return apiResourceResponse(PaymentGatewayResource::collection($paymentGateways));
    }

}
