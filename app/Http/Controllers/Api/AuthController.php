<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $validator = validateRequest($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:5|confirmed',
            'role' => ['nullable', Rule::in([
                UserRole::AFFILIATE->label(),
                UserRole::CUSTOMER->label(),
            ])],
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $data = $request->only(['name', 'email', 'phone', 'password', 'role']);
        $data['password'] = Hash::make($data['password']);
        $data['username'] = str_slug('users', 'username', $data['name']);
        $data['username'] = str_slug('users', 'username', $data['name']);

        $data['username'] = str_slug('users', 'username', $data['name']);

        if ($request->has('role') && $request->role === UserRole::AFFILIATE->label()) {
            $data['role'] = UserRole::AFFILIATE->value;
        } else {
            $data['role'] = UserRole::CUSTOMER->value;
        }

        $user = User::create($data);

        return apiResponse([
            'token' => $user->createToken("API TOKEN")->plainTextToken,
        ], 'Signup successful');
    }


    public function login(Request $request)
    {
        $validator = validateRequest($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $user = User::whereEmail($request->email)->first();

        if (!$user) {
            return errorResponse("No account found with this email!");
        }

        // if (!$user->is_active) {
        //     return errorResponse("Your account is not activated!");
        // }

        if (!Hash::check($request->password, $user->password)) {
            return errorResponse('Incorrect password!');
        }

        return apiResponse([
            'token' => $user->createToken("API TOKEN")->plainTextToken,
        ], 'Login Successful');
    }

    public function logout()
    {
        Auth::user()->tokens()->delete();

        return successResponse('Logged out successfully.');
    }

    public function sendResetCode(Request $request)
    {
        $validator = validateRequest($request, [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Send reset code
    }

    // Step 2: Verify Password Reset Code
    public function verifyResetCode(Request $request)
    {
        $validator = validateRequest($request, [
            'email' => 'required|email|exists:users,email',
            'reset_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    }

    // Step 3: Reset Password
    public function resetPassword(Request $request)
    {
        $validator = validateRequest($request, [
            'email' => 'required|email|exists:users,email',
            'reset_code' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }      
    }
}
