<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Seller;
use App\Models\SellerBannerImage;
use App\Models\State;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $seller = Seller::find(get_seller_id());
        $countries = Country::orderBy('name', 'ASC')->get();
        $states    = State::orderBy('name', 'ASC')->get();
        return view('seller.settings.index', compact('seller', 'countries', 'states'));
    }

    public function update(Request $request)
    {
        $seller = Seller::find(get_seller_id());

        $data = $request->validate([
            'business_name'    => 'required|string|max:255',
            'business_logo'    => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
            'business_email'   => 'required|string|email|max:255|unique:sellers,business_email,' . $seller->id,
            'business_address' => 'required|string|max:1000',
            'shipping_cost'    => 'nullable|numeric',
        ]);

        $username = $seller->username;

        if ($request->hasFile('business_logo')) {
            if (!empty($seller->business_logo)) {
                delete_file($seller->business_logo);
            }
            $data['business_logo'] = upload_file($request->file('business_logo'), "images/{$username}/logo");
        } else {
            $data['business_logo'] = $seller->business_logo;
        }

        $seller->update($data);

        // Handle new banner uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                SellerBannerImage::create([
                    'seller_id' => $seller->id,
                    'image'     => upload_file($file, "images/{$username}/banners"),
                ]);
            }
        }

        return redirect()->route('seller.settings.index')->with('success', 'Settings updated successfully');
    }


    public function deleteImage(SellerBannerImage $image)
    {
        delete_file($image->image);
        $image->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Banner image deleted successfully!'
        ]);
    }
}
