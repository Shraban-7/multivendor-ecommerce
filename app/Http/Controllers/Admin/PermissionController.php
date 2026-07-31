<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Auth\Models\Permission;
use App\Domain\Auth\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::get();

        return view('admin.permissions.index', compact('roles', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:roles,title',
        ]);

        $name = strtolower(str_replace(' ', '_', $request->title));

        Role::create([
            'title' => $request->title,
            'name' => $name,
        ]);

        return redirect()->route('admin.roles.index')->with('success', 'Role create successfully');
    }

    public function edit(Role $role)
    {
        $role->loadMissing('permissions');
        $permissions = Permission::get();

        return view('admin.permissions.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'title' => 'required|unique:roles,title,'.$role->id,
            'permissions' => 'required|array',
        ]);

        $name = strtolower(str_replace(' ', '_', $request->title));
        $role->title = $request->title;
        $role->name = $name;
        $role->save();

        $permissions = Permission::whereIn('id', $request->permissions)->pluck('id')->toArray();

        $role->permissions()->sync($permissions);

        Cache::forget("permissions_{$role->name}");

        return redirect()->back()->with('success', 'Permission updated successfully');
    }
}
