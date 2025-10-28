<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SellerController extends Controller
{

    // public function profile($username, Request $request)
    // {
    //     $seller = Seller::where('username', $username)->first();
    //     $divisions = Division::all();

    //     if ($request->isMethod('GET')) {
    //         return view('seller.profile', compact('seller','divisions'));
    //     }

    //     $data = $request->validate([
    //         'name'             => 'required|string|max:255',
    //         'email'            => 'required|string|email|max:255|unique:sellers,email,' . $seller->id,
    //         'phone'            => 'required|string',
    //         'business_name'    => 'required|string|max:255',
    //         'business_email'   => 'required|string|email|max:255|unique:sellers,email,' . $seller->id,
    //         'business_address' => 'required|string',
    //         'image'            => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
    //         'business_logo'    => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
    //     ]);

    //     if ($seller->name !== $request->name) {
    //         $data['username'] = str_slug('sellers', 'username', $request->name);
    //     } else {
    //         $data['username'] = $seller->username;
    //     }

    //     $usernameForPath = $data['username'];

    //     if ($request->hasFile('image')) {
    //         if (! empty($seller->image)) {
    //             delete_file($seller->image);
    //         }
    //         $imagePath     = "images/{$usernameForPath}/logo";
    //         $data['image'] = upload_file($request->file('image'), $imagePath);
    //     } else {
    //         $data['image'] = $seller->image;
    //     }

    //     if ($request->hasFile('business_logo')) {
    //         if (! empty($seller->business_logo)) {
    //             delete_file($seller->business_logo);
    //         }
    //         $logoPath              = "images/{$usernameForPath}/logo";
    //         $data['business_logo'] = upload_file($request->file('business_logo'), $logoPath);
    //     } else {
    //         $data['business_logo'] = $seller->business_logo;
    //     }

    //     $seller->update($data);

    //     return redirect()->back()->with('success', 'Profile Updated Successfully');
    // }


    public function profile(Request $request)
    {
        $seller = Auth::guard('seller')->user();
        $divisions = Division::all();

        if ($request->isMethod('GET')) {
            return view('seller.profile-information', compact('seller', 'divisions'));
        }

        $section = $request->input('section');

        try {
            $usernameForPath = $seller->username;
            if ($section === 'personal') {
                $data = $request->validate([
                    'name'  => 'required|string|max:255',
                    'email' => 'required|email|unique:sellers,email,' . $seller->id,
                    'phone' => 'required|string|max:20',
                    'nid_no' => 'nullable|string|max:100',
                    'image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
                    'nid_front_image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
                    'nid_back_image'  => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
                ]);

                $data['username'] = $seller->username;
                if ($seller->name !== $request->name) {
                    $data['username'] = str_slug('sellers', 'username', $request->name);
                    $usernameForPath = $data['username'];
                }

                $fileFields = ['image', 'nid_front_image', 'nid_back_image'];
                foreach ($fileFields as $field) {
                    if ($request->hasFile($field)) {
                        delete_file($seller->$field);
                        $data[$field] = upload_file($request->file($field), "images/{$usernameForPath}/profile");
                    } else {
                        $data[$field] = $seller->$field;
                    }
                }

                $seller->update($data);
            }
            elseif ($section === 'business') {
                $data = $request->validate([
                    'business_name'    => 'required|string|max:255',
                    'business_email'   => 'nullable|email|max:255|unique:sellers,business_email,' . $seller->id,
                    'business_address' => 'nullable|string|max:255',
                    'division_id'      => 'required|integer',
                    'district_id'      => 'required|integer',
                    'business_logo'    => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
                    'shop_image'       => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
                ]);

                $fileFields = ['business_logo', 'shop_image'];
                foreach ($fileFields as $field) {
                    if ($request->hasFile($field)) {
                        delete_file($seller->$field);
                        $folder = $field === 'business_logo' ? 'logo' : 'shop';
                        $data[$field] = upload_file($request->file($field), "images/{$usernameForPath}/{$folder}");
                    } else {
                        $data[$field] = $seller->$field;
                    }
                }

                $seller->update($data);
            }
            elseif ($section === 'documents') {
                $data = $request->validate([
                    'trade_license_no'   => 'nullable|string|max:255',
                    'trade_license_image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
                ]);

                if ($request->hasFile('trade_license_image')) {
                    delete_file($seller->trade_license_image);
                    $data['trade_license_image'] = upload_file($request->file('trade_license_image'), "images/{$usernameForPath}/documents");
                } else {
                    $data['trade_license_image'] = $seller->trade_license_image;
                }

                $seller->update($data);
            }
            else {
                return errorResponse('Invalid section provided.');
            }

            return successResponse('Profile updated successfully.');
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }
}
