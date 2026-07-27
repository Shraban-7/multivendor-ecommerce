<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Models\BillingAddress;
use App\Domain\Shipping\Models\Division;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        return view('frontend.profile', compact('user'));
    }

    public function addresses()
    {
        $user = Auth::user();
        $divisions = Division::get();
        $billingAddresses = BillingAddress::where('user_id', $user->id)
            ->latest()
            ->get();

        return view('frontend.addresses', compact('divisions', 'billingAddresses', 'user'));
    }

    public function updateAccount(Request $request)
    {
        $user = User::find(Auth::id());

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:2048',
        ]);

        if ($user->name !== $request->name) {
            $data['username'] = str_slug('users', 'username', $request->name);
        } else {
            $data['username'] = $user->username;
        }

        $data['phone'] = $request->phone;

        if ($request->hasFile('image')) {
            if (! empty($user->image)) {
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

        if (! Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('warning', 'Incorrect old password!');
        }

        $user->update(['password' => Hash::make($request->password)]);

        return redirect()->back()->with('success', 'Password updated successfully');
    }
}
