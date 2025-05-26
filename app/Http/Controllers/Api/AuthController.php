<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
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
            'password' => 'required|string|min:5|confirmed',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $data = $request->only(['name', 'email', 'password']);
        $data['password'] = Hash::make($data['password']);
        $data['username'] = str_slug('users', 'username', $data['name']);
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
}
