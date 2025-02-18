<?php

namespace App\Http\Controllers\frontend\auth;

use App\Models\User;
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

    public function login(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('frontend.auth.login');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Incorrect email!');
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            return redirect()->back()->with('error', 'Incorrect password!');
        }

        $request->session()->regenerate();

        session()->flash('success', 'Login successful');

        // return redirect_intended('/');

        return redirect()->route('profile');
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
        $data['displayname'] = $request->displayname;
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
