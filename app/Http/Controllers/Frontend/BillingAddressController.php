<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Order\Models\BillingAddress;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BillingAddressController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'division_id' => 'required',
            'district_id' => 'required',
            'address' => 'required|string',
            'type' => 'required|numeric|in:1,2',
            'is_default' => 'required|boolean',
        ]);

        $user_id = Auth::id();

        $data['user_id'] = $user_id;

        if ($data['is_default'] == true) {
            BillingAddress::where('user_id', $user_id)
                ->where('is_default', true)
                ->update([
                    'is_default' => false,
                ]);
        }

        BillingAddress::create($data);

        return redirect()->back()->with('success', 'Billing address added successfully');
    }

    public function update(Request $request, BillingAddress $address)
    {
        $data = $request->validate([
            'customer_name' => 'required|string',
            'customer_phone' => 'required|string',
            'division_id' => 'required',
            'district_id' => 'required',
            'address' => 'required|string',
            'type' => 'required|numeric|in:1,2',
            'is_default' => 'nullable|boolean',
        ]);

        $user_id = Auth::id();

        $data['user_id'] = $user_id;

        if ($data['is_default'] == true) {
            if ($data['is_default'] == $address->is_default) {
                $data['is_default'] = true;
            } else {
                BillingAddress::where('user_id', $user_id)
                    ->where('is_default', true)
                    ->update([
                        'is_default' => false,
                    ]);
            }
        }

        $address->update($data);

        return redirect()->back()->with('success', 'Billing address updated successfully');
    }
}
