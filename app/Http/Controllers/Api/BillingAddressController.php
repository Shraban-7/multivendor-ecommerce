<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BillingAddressResource;
use App\Models\BillingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingAddressController extends Controller
{
    public function index()
    {
        $addresses = BillingAddress::where('user_id', Auth::id())->orderBy('is_default', 'DESC')->get();

        return apiResourceResponse(BillingAddressResource::collection($addresses));
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'division_id' => 'required|exists:divisions,id',
            'district_id' => 'required|exists:districts,id',
            'address' => 'required|string',
            'type' => 'required|numeric|in:1,2',
            'is_default' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $user_id = Auth::id();
        $data = $validator->validated();
        $data['user_id'] = $user_id;

        if ($data['is_default'] == 1) {
            BillingAddress::where('user_id', $user_id)
                ->update([
                    'is_default' => false
                ]);
        }

        BillingAddress::create($data);

        return successResponse("Billing address added successfully");
    }

    public function update(BillingAddress $address, Request $request)
    {
        $validator = validateRequest($request, [
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'division_id' => 'required|exists:divisions,id',
            'district_id' => 'required|exists:districts,id',
            'address' => 'required|string',
            'type' => 'required|numeric|in:1,2',
            'is_default' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $data = $validator->validated();

        if ($data['is_default'] == 1) {
            BillingAddress::where('user_id', Auth::id())
                ->update([
                    'is_default' => false
                ]);
        }

        $address->update($data);

        return successResponse("Billing address updated successfully");
    }
}
