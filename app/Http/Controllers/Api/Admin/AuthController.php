<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Auth\Models\Admin;
use App\Domain\Auth\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = validateRequest($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        $admin = Admin::where('email', $request->email)->first();

        if (! $admin) {
            return errorResponse('No account found with this email!');
        }

        if (! Hash::check($request->password, $admin->password)) {
            return errorResponse('Incorrect password!');
        }

        return apiResponse([
            'admin' => new AdminResource($admin),
            'token' => $admin->createToken('admin-api-token')->plainTextToken,
        ], 'Login successful');
    }

    public function signup(Request $request)
    {
        $validator = validateRequest($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        $defaultRole = Role::first();
        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => strtolower(str_replace(' ', '_', $request->name)),
            'password' => Hash::make($request->password),
            'role_id' => $defaultRole?->id,
        ]);

        return apiResponse([
            'admin' => new AdminResource($admin),
            'token' => $admin->createToken('admin-api-token')->plainTextToken,
        ], 'Admin created successfully.');
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return successResponse('Logged out successfully.');
    }

    public function profile()
    {
        return apiResponse(new AdminResource(Auth::user()));
    }

    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $validator = validateRequest($request, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:admins,email,'.$admin->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $data = $validator->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $admin->update($data);

        return apiResponse(new AdminResource($admin->fresh()), 'Profile updated successfully.');
    }
}
