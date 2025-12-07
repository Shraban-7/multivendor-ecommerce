<?php

namespace App\Http\Controllers;

use App\Models\OtpLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private function phoneValidationRules($unique = false)
    {
        return User::phoneValidationRules($unique);
    }

    public function checkPhone(Request $request)
    {
        $validator = validateRequest($request, [
            'phone' => $this->phoneValidationRules()
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        $phone = $request->phone;

        $user = User::where('phone', $phone)->first();
        if ($user) {
            return apiResponse([
                'user_exists' => true
            ]);
        }

        OtpLog::generate($phone, OtpLog::TYPE_SIGNUP);

        return apiResponse([
            'user_exists' => false,
        ], 'OTP sent successfully');
    }

    public function verifyOtp(Request $request)
    {
        $validator = validateRequest($request, [
            'phone' => $this->phoneValidationRules(),
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        $phone = $request->phone;

        $otpLog = OtpLog::verify($phone, $request->otp, OtpLog::TYPE_SIGNUP);
        if (!$otpLog) {
            return errorResponse('Invalid or expired verification code.');
        }

        $user = User::where('phone', $phone)->first();

        return apiResponse([
            'message' => 'OTP verified.',
            'is_existing_user' => (bool)$user,
        ]);
    }

    public function login(Request $request)
    {
        $validator = validateRequest($request, [
            'phone' => $this->phoneValidationRules(),
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        $credentials = [
            'phone' => $request->phone,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            session()->flash('success', 'Login successful');

            return successResponse('Login successful');
        }

        return errorResponse('Incorrect password!');
    }

    public function register(Request $request)
    {
        $validator = validateRequest($request, [
            'phone' => $this->phoneValidationRules(true),
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'username' => User::generateShortUsername($request->phone),
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        session()->flash('success', 'Registration successful');

        return successResponse('Registration successful', 201);
    }
}
