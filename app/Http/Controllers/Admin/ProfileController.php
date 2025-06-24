<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function profile()
    {
        $admin = Auth::guard('admin')->user();
        return view('admin.profile', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'nullable|confirmed|string|min:6',
        ]);

        $password = $request->password != '' ? Hash::make($request->password) : $admin->password;

        $admin->update([
            'name'     => $request->name,
            'username' => Str::slug($request->name),
            'email'    => $request->email,
            'password' => $password,
            'role_id'  => $admin->role_id,
        ]);

        return redirect()->back()->with('success', "Profile updated successfully");
    }
}
