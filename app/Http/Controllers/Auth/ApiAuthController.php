<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ApiAuthController extends Controller
{
    // --- Mock Database / OTP Store (Replace with Redis/Database for production) ---
    private static $otpStore = [];

    /**
     * Step 1: Check if phone exists and "sends" OTP.
     */
    public function checkPhone(Request $request)
    {
        $request->validate(['phone' => 'required|string|regex:/^\+8801[3-9]\d{8}$/|max:14']);
        $phone = $request->phone;

        // --- MOCK OTP GENERATION ---
        $otp = '123456'; // Use a real OTP service here (Vonage/Twilio)
        self::$otpStore[$phone] = ['code' => $otp, 'expires_at' => now()->addMinutes(5)];
        // You would call your SMS service here (e.g., $smsService->send($phone, $otp);)

        return response()->json([
            'message' => 'OTP sent successfully.',
        ]);
    }

    /**
     * Step 2: Verify OTP and determine next step (Login or Register).
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^\+8801[3-9]\d{8}$/|max:14',
            'otp' => 'required|digits:6',
        ]);

        $phone = $request->phone;
        $inputOtp = $request->otp;
        $storedOtpData = self::$otpStore[$phone] ?? null;

        // 1. Check if OTP is valid
        if (!$storedOtpData || $storedOtpData['code'] !== $inputOtp || $storedOtpData['expires_at']->isPast()) {
            // You should only tell the user that the code is invalid, not why.
            //throw ValidationException::withMessages(['otp' => 'Invalid or expired verification code.']);
        }

        // 2. Clear OTP after successful verification
        unset(self::$otpStore[$phone]);

        // 3. Check if user exists
        $user = User::where('phone', $phone)->first();

        return response()->json([
            'message' => 'OTP verified.',
            'is_existing_user' => (bool)$user,
        ]);
    }

    /**
     * Step 3: Login returning user with phone and password.
     */
    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^\+8801[3-9]\d{8}$/|max:14',
            'password' => 'required|string|min:6',
        ]);

        $credentials = [
            'phone' => $request->phone,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            
            return response()->json(['message' => 'Login successful.'], 200);
        }

        throw ValidationException::withMessages(['password' => 'Invalid password.']);
    }

    /**
     * Step 4: Register new user.
     */
    public function register(Request $request)
    {
        $request->validate([
            'phone' => 'required|unique:users,phone|string|regex:/^\+8801[3-9]\d{8}$/|max:14',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'username' =>User::generateShortUsername($request->phone),
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return response()->json(['message' => 'Registration successful.'], 201);
    }
}
