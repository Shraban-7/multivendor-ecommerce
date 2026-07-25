<?php

namespace App\Http\Controllers;

use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerEmployee;
use App\Models\Admin;
use App\Models\OtpLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private function phoneValidationRules($unique = false)
    {
        return User::phoneValidationRules($unique);
    }

    public function checkPhone(Request $request)
    {
        $validator = validateRequest($request, [
            'phone' => $this->phoneValidationRules(),
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        $phone = $request->phone;

        $models = [
            Admin::class,
            User::class,
            Seller::class,
            SellerEmployee::class,
        ];

        foreach ($models as $model) {
            $user = $model::where('phone', $phone)->first();

            if ($user) {
                return apiResponse([
                    'user_exists' => true,
                ]);
            }
        }

        $otpLog = OtpLog::generate($phone, OtpLog::TYPE_SIGNUP);

        $otpMessage = "{$otpLog->code} is your Slash Mart verification code. Valid for 5 min";

        send_sms($otpMessage, $phone);

        $remainingSeconds = max(
            now()->diffInSeconds($otpLog->expires_at, false),
            0
        );

        return apiResponse([
            'user_exists' => false,
            'remaining_otp_time' => $remainingSeconds,
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
        if (! $otpLog) {
            return errorResponse('Invalid or expired verification code.');
        }

        $user = User::where('phone', $phone)->first();

        return apiResponse([
            'message' => 'OTP verified.',
            'is_existing_user' => (bool) $user,
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

        $userTypes = [
            'admin' => [
                'model' => Admin::class,
                'guard' => 'admin',
                'check' => fn ($admin) => true,
            ],
            'user' => [
                'model' => User::class,
                'guard' => 'web',
                'check' => fn ($user) => true,
            ],
            'seller' => [
                'model' => Seller::class,
                'guard' => 'seller',
                'check' => fn ($seller) => $seller->status == Seller::ACTIVE,
                'inactiveMessage' => 'Your account is inactive, contact with admin',
            ],
            'employee' => [
                'model' => SellerEmployee::class,
                'guard' => 'employee',
                'check' => fn ($employee) => $employee->is_active == 1,
                'inactiveMessage' => 'Your account is inactive, contact with seller',
            ],

        ];

        foreach ($userTypes as $type => $config) {
            $model = $config['model'];

            $user = $model::where('phone', $request->phone)->first();
            if (! $user) {
                continue;
            }

            if ($type === 'seller') {
                if ($user->status == Seller::BLOCKED) {
                    return errorResponse('Your account has been blocked. Contact admin.', 403);
                }

                if ($user->status != Seller::ACTIVE) {
                    return errorResponse('Your account is pending approval.', 403);
                }
            }

            if (! ($config['check'])($user)) {
                return errorResponse($config['inactiveMessage'] ?? 'Account inactive', 403);
            }

            if (! Auth::guard($config['guard'])->attempt($credentials)) {
                return errorResponse('Incorrect password!');
            }

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
