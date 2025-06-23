<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::get();

        return view('admin.members.index', compact('admins'));
    }

    public function add()
    {
        $roles =  Role::get();

        return view('admin.members.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'role_id' => 'required|numeric|exists:roles,id',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|confirmed|string|min:6',
        ]);

        $name = $request->name;

        Admin::Create([
            'name' => $name,
            'username' => strtolower(str_replace(' ', '_', $name)),
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin Added Successfully');
    }

    public function edit(Admin $admin)
    {
        $roles =  Role::get();

        return view('admin.members.edit', compact('admin', 'roles'));
    }

    public function update(Admin $admin, Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'role_id' => 'required|numeric|exists:roles,id',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
            'password' => 'required|confirmed|string|min:6',
        ]);

        $password = $request->password != '' ? Hash::make($request->password) : $admin->password;

        $admin->update([
            'name' => $request->name,
            'username' => Str::slug($request->name),
            'email' => $request->email,
            'password' => $password,
            'role_id' => $request->role_id,
        ]);

        return redirect()->back()->with('success', "Admin updated successfully");
    }

    public function delete(Admin $admin)
    {
        $admin->delete();

        return redirect()->route('admin.admins.index')->with('success', 'Admin deleted successfully');
    }
}
