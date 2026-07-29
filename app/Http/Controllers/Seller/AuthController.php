<?php

namespace App\Http\Controllers\Seller;

use App\Domain\Shipping\Models\Country;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerEmployee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('seller.auth.login');
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $seller = Seller::where('email', $request->email)->first();

        if ($seller) {
            if ($seller->status == Seller::BLOCKED) {
                return redirect()->back()->withInput($request->only('email'))
                    ->with('warning', 'Your account has been blocked. Contact admin.');
            }

            if ($seller->status != Seller::ACTIVE) {
                return redirect()->back()->withInput($request->only('email'))
                    ->with('warning', 'Your account is pending approval. Please wait for admin review.');
            }

            if (! Auth::guard('seller')->attempt($credentials, $request->boolean('remember'))) {
                return redirect()->back()->withInput($request->only('email'))
                    ->with('error', 'Incorrect password!');
            }

            $request->session()->regenerate();

            return redirect()->route('seller.dashboard')->with('success', 'Login successful');
        }

        $employee = SellerEmployee::where('email', $request->email)->first();

        if ($employee) {
            if ((int) $employee->is_active !== 1) {
                return redirect()->back()->withInput($request->only('email'))
                    ->with('warning', 'Your account is inactive, contact with seller');
            }

            if (! Auth::guard('employee')->attempt($credentials, $request->boolean('remember'))) {
                return redirect()->back()->withInput($request->only('email'))
                    ->with('error', 'Incorrect password!');
            }

            $request->session()->regenerate();

            return redirect()->route('seller.dashboard')->with('success', 'Login successful');
        }

        return redirect()->back()->withInput($request->only('email'))
            ->with('error', 'Incorrect email!');
    }

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

        return redirect()->route('seller.login')->with('success', 'Signup successful! Please log in.');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('seller')->check()) {
            Auth::guard('seller')->logout();
        }

        if (Auth::guard('employee')->check()) {
            Auth::guard('employee')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('seller.login');
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
            'email' => 'required|string|email|max:255|unique:sellers,email,'.$seller->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        if ($seller->name !== $request->name) {
            $data['username'] = str_slug('sellers', 'username', $request->name);
        } else {
            $data['username'] = $seller->username;
        }

        $data['phone'] = $request->phone;

        if ($request->hasFile('image')) {
            if (! empty($seller->image)) {
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

        if (! Hash::check($request->current_password, $seller->password)) {
            return redirect()->back()->with('warning', 'Incorrect old password!');
        }

        $seller->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'Password updated successfully');
    }
}
