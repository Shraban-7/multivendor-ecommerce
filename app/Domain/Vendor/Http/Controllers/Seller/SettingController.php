<?php

namespace App\Domain\Vendor\Http\Controllers\Seller;

use App\Domain\Shipping\Models\Division;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerBannerImage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $seller = Seller::find(get_seller_id());
        $divisions = Division::orderBy('name', 'ASC')->get();

        return view('seller.settings.index', compact('seller', 'divisions'));
    }

    public function update(Request $request)
    {
        $seller = Seller::find(get_seller_id());

        $data = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
            'business_email' => 'required|string|email|max:255|unique:sellers,business_email,'.$seller->id,
            'business_address' => 'required|string|max:1000',
            'business_description' => 'nullable|string|max:5000',
            'shop_type' => 'nullable|string|max:20|in:individual,business,company',
            'division_id' => 'nullable|integer|exists:divisions,id',
            'district_id' => 'nullable|integer|exists:districts,id',
            'shipping_cost' => 'nullable|numeric',
            'nid_no' => 'nullable|string|max:100',
            'trade_license_no' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:2048',
            'nid_front_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:2048',
            'nid_back_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:2048',
            'trade_license_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:2048',
            'cover_image' => 'nullable|mimes:jpg,png,webp,svg,jpeg,avif,gif|max:4096',
            'shop_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
            'banner' => 'nullable|mimes:jpg,png,webp,svg,jpeg,avif,gif|max:4096',
        ]);

        $username = $seller->username;

        if ($request->hasFile('business_logo')) {
            if (! empty($seller->business_logo)) {
                delete_file($seller->business_logo);
            }
            $data['business_logo'] = upload_file($request->file('business_logo'), "{$username}");
        } else {
            $data['business_logo'] = $seller->business_logo;
        }

        if ($request->hasFile('cover_image')) {
            if (! empty($seller->cover_image)) {
                delete_file($seller->cover_image);
            }
            $data['cover_image'] = upload_file($request->file('cover_image'), "{$username}");
        } else {
            $data['cover_image'] = $seller->cover_image;
        }

        if ($request->hasFile('shop_image')) {
            if (! empty($seller->shop_image)) {
                delete_file($seller->shop_image);
            }
            $data['shop_image'] = upload_file($request->file('shop_image'), "{$username}");
        } else {
            $data['shop_image'] = $seller->shop_image;
        }

        foreach (['image', 'nid_front_image', 'nid_back_image', 'trade_license_image'] as $field) {
            if ($request->hasFile($field)) {
                if (! empty($seller->$field)) {
                    delete_file($seller->$field);
                }
                $data[$field] = upload_file($request->file($field), "images/{$username}/documents");
            } else {
                $data[$field] = $seller->$field;
            }
        }

        $seller->update($data);

        $existBanner = SellerBannerImage::where('seller_id', $seller->id)->first();

        if ($request->hasFile('banner')) {

            if ($existBanner && ! empty($existBanner->image)) {
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
            'message' => 'Banner image deleted successfully!',
        ]);
    }
}
