<?php

namespace App\Http\Controllers\Frontend;

use App\Models\User;
use App\Models\Seller;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('frontend.auth.signup');
        }

        $data = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5|confirmed',
        ]);

        $data['username'] = str_slug('users', 'username', $data['fullname']);

        User::create($data);

        return redirect()->route('login')->with('success', 'Signup successful! Please log in.');
    }

    public function sellerSignup(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('frontend.auth.seller-signup');
        }

        $data = $request->validate([
            'fullname' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
            'email' => 'required|string|email|max:255|unique:sellers,email',
            'phone' => 'required|string|max:200',
            'nid_no' => 'required|string|max:50',
            'nid_front_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
            'nid_back_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
            'password' => 'required|string|min:5|confirmed',

            'business_name' => 'required|string|max:255',
            'business_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
            'business_email' => 'required|string|email|max:255|unique:sellers,business_email',
            'business_address' => 'required|string|max:1000',
            'trade_license_no' => 'required|string|max:100',
            'trade_license_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
            'shop_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',

            'country_id' => 'nullable|integer|exists:countries,id',
            'state_id' => 'nullable|integer',
            'zip' => 'nullable|string|max:20',
        ]);

        $data['username'] = str_slug('sellers', 'username', $data['fullname']);

        $imageFields = [
            'image' => 'images/sellers/avatar',
            'nid_front_image' => 'images/sellers/nids',
            'nid_back_image' => 'images/sellers/nids',
            'business_logo' => 'images/sellers/business',
            'trade_license_image' => 'images/sellers/licenses',
            'shop_image' => 'images/sellers/shops',
        ];

        foreach ($imageFields as $field => $folder) {
            if ($request->hasFile($field)) {
                $data[$field] = upload_file($request->file($field), $folder);
            }
        }

        Seller::create($data);

        return redirect()->route('login')->with('success', 'Signup successful! Please log in.');
    }


    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }

    public function profile()
    {
        $countries = Country::all();
        return view('frontend.profile', compact('countries'));
    }

    public function updateAccount(Request $request)
    {
        $user = User::find(Auth::id());

        $data = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        if ($user->fullname !== $request->fullname) {
            $data['username'] = str_slug('users', 'username', $request->fullname);
        } else {
            $data['username'] = $user->username;
        }


        $data['phone'] = $request->phone;
        $data['display_name'] = $request->display_name;
        $data['secondary_email'] = $request->secondary_email;
        $data['country_id'] = $request->country_id;

        if ($request->hasFile('image')) {
            if (!empty($user->image)) {
                delete_file($user->image);
            }

            $filePath = 'images/user/avatar';
            $data['image'] = upload_file($request->file('image'), $filePath);
        } else {
            $data['image'] = $user->image;
        }


        $user->update($data);

        return redirect()->back()->with('success', 'Account update successfully');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with("warning", "Incorrect old password!");
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with("success", "Password updated successfully");
    }

}
