<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if ($request->isMethod('GET')) {
            return view('admin.profile', compact('admin'));

        }

        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:admins,email,'.$admin->id,
            'password' => 'nullable|confirmed|string|min:6',
        ]);

        $password = $request->password != '' ? Hash::make($request->password) : $admin->password;

        $admin->update([
            'name' => $request->name,
            'username' => Str::slug($request->name),
            'email' => $request->email,
            'password' => $password,
            'role_id' => $admin->role_id,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully');
    }
}
