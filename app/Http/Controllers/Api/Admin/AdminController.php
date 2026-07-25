<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Auth\Models\Admin;
use App\Domain\Auth\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::with('role:id,title')->latest()->get();

        return apiResponse($admins->map(fn ($a) => [
            'id' => $a->id,
            'name' => $a->name,
            'username' => $a->username,
            'email' => $a->email,
            'role' => $a->role?->title,
            'role_id' => $a->role_id,
            'created_at' => $a->created_at,
        ]));
    }

    public function create()
    {
        $roles = Role::get(['id', 'title']);

        return apiResponse(['roles' => $roles]);
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => strtolower(str_replace(' ', '_', $request->name)),
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return apiResponse([
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
        ], 'Admin created successfully.');
    }

    public function edit(Admin $admin)
    {
        $roles = Role::get(['id', 'title']);

        return apiResponse([
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role_id' => $admin->role_id,
            ],
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, Admin $admin)
    {
        $validator = validateRequest($request, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:admins,email,'.$admin->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role_id' => 'sometimes|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $data = $request->only(['name', 'email', 'role_id']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $admin->update($data);

        return successResponse('Admin updated successfully.');
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return successResponse('Admin deleted successfully.');
    }
}
