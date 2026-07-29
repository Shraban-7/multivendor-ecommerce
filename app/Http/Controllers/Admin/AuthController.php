<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('admin.auth.login');
        }

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (! Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            return redirect()->back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid email or password.');
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')->with('success', 'Login successful');
    }

    public function signup(Request $request)
    {
        // Admin accounts are created from the panel — keep the guest route stable.
        return redirect()->route('admin.login');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
