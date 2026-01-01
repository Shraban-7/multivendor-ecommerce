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
        $states = State::orderBy('name', 'ASC')->get();
        return view('seller.settings.index', compact('seller', 'countries', 'states'));
    }

    public function update(Request $request)
    {
        $seller = Seller::find(get_seller_id());

        $data = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
            'business_email' => 'required|string|email|max:255|unique:sellers,business_email,' . $seller->id,
            'business_address' => 'required|string|max:1000',
            'shipping_cost' => 'nullable|numeric',
            'cover_image' => 'nullable|mimes:jpg,png,webp,svg,jpeg,avif,gif|max:4096',
            'banner' => 'nullable|mimes:jpg,png,webp,svg,jpeg,avif,gif|max:4096'
        ]);

        $username = $seller->username;

        if ($request->hasFile('business_logo')) {
            if (!empty($seller->business_logo)) {
                delete_file($seller->business_logo);
            }
            $data['business_logo'] = upload_file($request->file('business_logo'), "{$username}");
        } else {
            $data['business_logo'] = $seller->business_logo;
        }

        if ($request->hasFile('cover_image')) {
            if (!empty($seller->cover_image)) {
                delete_file($seller->cover_image);
            }
            $data['cover_image'] = upload_file($request->file('cover_image'), "{$username}");
        } else {
            $data['cover_image'] = $seller->cover_image;
        }

        $seller->update($data);

        $existBanner = SellerBannerImage::where('seller_id', $seller->id)->first();

        if ($request->hasFile('banner')) {

            if ($existBanner && !empty($existBanner->image)) {
                delete_file($existBanner->image);
                $existBanner->delete();
            }

            SellerBannerImage::create([
                'seller_id' => $seller->id,
                'image' => upload_file(
                    $request->file('banner'),
                    "{$username}"
                ),
            ]);
        }


        return redirect()->route('seller.settings.index')->with('success', 'Settings updated successfully');
    }


    public function deleteImage(SellerBannerImage $image)
    {
        delete_file($image->image);
        $image->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Banner image deleted successfully!'
        ]);
    }
}
