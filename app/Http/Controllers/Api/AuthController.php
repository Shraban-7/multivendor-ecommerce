<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\VerificationCode;
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
            return sendValidationError($validator->error());
        }

        $email = $request->email;

        $account = User::where('email', $email)->first();

        if (!$account) {
            return errorResponse('No account found with this email.');
        }

        $code = VerificationCode::generateCode();

        VerificationCode::where('email', $email)
            ->where('type', VerificationCode::PASSWORD_RESET)
            ->whereNull('used_at')
            ->delete();

        VerificationCode::create([
            'email'      => $email,
            'phone'      => $account->phone ?? null,
            'code'       => $code,
            'type'       => VerificationCode::PASSWORD_RESET,
            'expires_at' => now()->addMinutes(10),
        ]);

        return successResponse('Password Reset Code Send Successfully');
    }

    public function verifyResetCode(Request $request)
    {
        $validator = validateRequest($request, [
            'email' => 'required|email|exists:users,email',
            'reset_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $email = $request->email;
        $code = $request->reset_code;

        $verification_code = VerificationCode::where('email', $email)
            ->where('type', VerificationCode::PASSWORD_RESET)
            ->whereNull('used_at')
            ->latest()
            ->first();

        if (!$verification_code) {
            return errorResponse('No reset code found or it has already been used.');
        }

        if ($verification_code->code !== $code) {
            return errorResponse('Invalid reset code.');
        }

        if (now()->greaterThan($verification_code->expires_at)) {
            return errorResponse('Reset code has expired.');
        }

        $verification_code->update(['used_at' => now()]);

        return successResponse('Reset code verified successfully.');
    }

    public function resetPassword(Request $request)
    {
        $validator = validateRequest($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $email = $request->email;
        $password = $request->password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return errorResponse('No account found with this email.');
        }

        $user->update([
            'password' => Hash::make($password),
        ]);

        VerificationCode::where('email', $email)
            ->where('type', VerificationCode::PASSWORD_RESET)
            ->whereNull('used_at')
            ->update(['used_at' => now()]);

        return successResponse('Password reset successfully. Please login with your new password.');
    }
}
