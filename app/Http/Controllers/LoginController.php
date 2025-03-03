<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('frontend.auth.login');
        }

        $user = User::where('email', $request->email)->first();
        $seller = Seller::where('email', $request->email)->first();
        $admin = Admin::where('email', $request->email)->first();

        if (!$user && !$seller && !$admin) {
            return redirect()->back()->with('error', 'Incorrect email!');
        }

        if ($user) {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return redirect()->back()->with('error', 'Incorrect password!');
            }

            $request->session()->regenerate();
            session()->flash('success', 'Login successful');

            return redirect()->intended('profile');
        }

        if ($seller) {
            if (!Auth::guard('seller')->attempt($request->only('email', 'password'))) {
                return redirect()->back()->with('error', 'Incorrect password!');
            }

            $request->session()->regenerate();
            session()->flash('success', 'Login successful');

            return redirect()->route('seller.dashboard')->with('success','You successfully login');
        }

        if ($admin) {
            if (!Auth::guard('admin')->attempt($request->only('email', 'password'))) {
                return redirect()->back()->with('error', 'Incorrect password!');
            }

            $request->session()->regenerate();
            session()->flash('success', 'Login successful');

            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->with('error', 'Something went wrong.')->with('success', 'You successfully login');
    }

}
