<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Auth\Models\Permission;
use App\Domain\Auth\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions:id,name')->get();

        return apiResponse($roles->map(fn ($r) => [
            'id' => $r->id,
            'title' => $r->title,
            'name' => $r->name,
            'permissions' => $r->permissions->pluck('name'),
            'admins_count' => $r->admins()->count(),
            'created_at' => $r->created_at,
        ]));
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'title' => 'required|string|max:255|unique:roles,title',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $role = Role::create([
            'title' => $request->title,
            'name' => strtolower(str_replace(' ', '_', $request->title)),
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return apiResponse([
            'id' => $role->id,
            'title' => $role->title,
        ], 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissions = Permission::get(['id', 'name']);

        return apiResponse([
            'role' => $role,
            'all_permissions' => $permissions,
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $validator = validateRequest($request, [
            'title' => 'required|string|max:255|unique:roles,title,'.$role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $role->update([
            'title' => $request->title,
            'name' => strtolower(str_replace(' ', '_', $request->title)),
        ]);

        $role->permissions()->sync($request->permissions);

        Cache::forget("permissions_{$role->name}");

        return successResponse('Role updated successfully.');
    }
}
