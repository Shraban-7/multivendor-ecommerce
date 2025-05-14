<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $seller = seller();
        $countries = Country::orderBy('name', 'ASC')->get();
        $states = State::orderBy('name', 'ASC')->get();
        return view('seller.settings.index', compact('seller', 'countries', 'states'));
    }

    public function update(Request $request)
    {
        $seller = seller();
        $data =  $request->validate([
            'business_name' => 'required|string|max:255',
            'business_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
            'business_email' => 'required|string|email|max:255|unique:sellers,business_email,' . $seller->id,
            'business_address' => 'required|string|max:1000',
            'shop_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
            'country_id' => 'nullable|integer|exists:countries,id',
            'state_id' => 'nullable|integer',
            'zip' => 'nullable|string|max:20',
            'shipping_cost' => 'nullable|numeric'
        ]);

        if ($request->hasFile('business_logo')) {
            if ($seller->business_logo != null) {
                delete_file($seller->business_logo);
            }

            $data['business_logo'] = upload_file($request->file('business_logo'), 'images/sellers/business');
        }

        if ($request->hasFile('shop_image')) {
            if ($seller->shop_image != null) {
                delete_file($seller->shop_image);
            }

            $data['shop_image'] = upload_file($request->file('shop_image'), 'images/sellers/shops');
        }

        $seller->update($data);

        return redirect()->route('seller.settings.index')->with('success','Setting update successfully');
    }
}
