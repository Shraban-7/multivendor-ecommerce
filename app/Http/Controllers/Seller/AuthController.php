<?php

namespace App\Http\Controllers\Seller;

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
            return view('seller.auth.signup');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:sellers',
            'password' => 'required|string|min:5|confirmed',
        ]);

        $data['username'] = str_slug('sellers', 'username', $data['name']);

        Seller::create($data);

        return redirect()->route('home')->with('success', 'Signup successful! Please log in.');
    }

    public function logout()
    {
        if (Auth::guard('seller')->check()) {
            Auth::guard('seller')->logout();
        } elseif (Auth::guard('employee')->check()) {
            Auth::guard('employee')->logout();
        }

        return redirect()->route('home');
    }


    public function profile()
    {
        $countries = Country::all();
        return view('frontend.profile', compact('countries'));
    }

    public function updateAccount(Request $request)
    {
        $seller = Seller::find(Auth::id());

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:sellers,email,' . $seller->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        if ($seller->name !== $request->name) {
            $data['username'] = str_slug('sellers', 'username', $request->name);
        } else {
            $data['username'] = $seller->username;
        }


        $data['phone'] = $request->phone;

        if ($request->hasFile('image')) {
            if (!empty($seller->image)) {
                delete_file($seller->image);
            }

            $filePath = 'images/sellers/avatar';
            $data['image'] = upload_file($request->file('image'), $filePath);
        } else {
            $data['image'] = $seller->image;
        }


        $seller->update($data);

        return redirect()->back()->with('success', 'Account update successfully');
    }

    public function updatePassword(Request $request)
    {
        $seller = Auth::guard('seller')->user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed',
        ]);

        if (!Hash::check($request->current_password, $seller->password)) {
            return redirect()->back()->with("warning", "Incorrect old password!");
        }

        $seller->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with("success", "Password updated successfully");
    }
}
