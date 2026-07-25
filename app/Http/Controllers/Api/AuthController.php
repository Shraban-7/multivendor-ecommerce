<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\OtpLog;
use App\Models\User;
use App\Models\VerificationCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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

        $user = User::where('phone', $phone)->first();
        if ($user) {
            return apiResponse([
                'user_exists' => true,
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
        if (! $otpLog) {
            return errorResponse('Invalid or expired verification code.');
        }

        $user = User::where('phone', $phone)->first();

        return apiResponse([
            'message' => 'OTP verified.',
            'is_existing_user' => (bool) $user,
        ]);
    }

    public function register(Request $request)
    {
        $validator = validateRequest($request, [
            'phone' => $this->phoneValidationRules(true),
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['nullable', Rule::in([
                UserRole::AFFILIATE->label(),
                UserRole::CUSTOMER->label(),
            ])],
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

        return apiResponse([
            'user' => (new UserResource($user)),
            'token' => $user->createToken('API TOKEN')->plainTextToken,
        ], 'Signup successful');
    }

    public function signup(Request $request)
    {
        $validator = validateRequest($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
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

        if ($request->has('role') && $request->role === UserRole::AFFILIATE->label()) {
            $data['role'] = UserRole::AFFILIATE->value;
        } else {
            $data['role'] = UserRole::CUSTOMER->value;
        }

        $user = User::create($data);

        $user->sendEmailVerificationMail();

        return apiResponse([
            'token' => $user->createToken('API TOKEN')->plainTextToken,
        ], 'Signup successful');
    }

    public function login(Request $request)
    {
        $validator = validateRequest($request, [
            'phone' => $this->phoneValidationRules(),
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $user = User::where('phone', $request->phone)->first();

        if (! $user) {
            return errorResponse('No account found with this phone!');
        }

        if (! Hash::check($request->password, $user->password)) {
            return errorResponse('Incorrect password!');
        }

        return apiResponse([
            'user' => (new UserResource($user)),
            'token' => $user->createToken('API TOKEN')->plainTextToken,
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

        $code = VerificationCode::generateCode();

        VerificationCode::create([
            'email' => $email,
            'code' => $code,
            'type' => VerificationCode::PASSWORD_RESET,
            'expires_at' => now()->addMinutes(VerificationCode::EXPIRY_MINUTES),
        ]);

        return successResponse('Password reset code sent successfully');
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

        if (! $verification_code) {
            return errorResponse('No reset code found or it has already been used.');
        }

        if ($verification_code->code !== $code) {
            return errorResponse('Invalid reset code.');
        }

        if (now()->greaterThan($verification_code->expires_at)) {
            return errorResponse('Reset code has expired.');
        }

        return successResponse('Reset code verified successfully.');
    }

    public function setPassword(Request $request)
    {
        $validator = validateRequest($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'reset_code' => 'required',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        User::where('email', $request->email)
            ->update([
                'password' => Hash::make($request->password),
            ]);

        VerificationCode::where('code', $request->reset_code)
            ->where('email', $request->email)
            ->update([
                'used_at' => now(),
            ]);

        return successResponse('Password reset successfully.');
    }

    public function resendCode(Request $request)
    {
        $validator = validateRequest($request, [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->error());
        }

        $email = $request->email;

        $alreadySent = VerificationCode::where('email', $email)
            ->where('type', VerificationCode::EMAIL_VERIFICATION)
            ->whereNull('used_at')
            ->latest('id')
            ->first();

        if ($alreadySent && $alreadySent->expiry_at && $alreadySent->expiry_at > now()) {
            $timeLeft = now()->diffForHumans($alreadySent->expiry_at, false, true);

            return errorResponse("Please wait for {$timeLeft} to request another code.");
        }

        User::where('email', $email)->first()->sendEmailVerificationMail();

        return successResponse('Verification code sent successfully');
    }

    public function verifyEmailCode(Request $request)
    {
        $validator = validateRequest($request, [
            'email' => 'required|email|exists:users,email',
            'code' => 'required|max:6',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if ($user && $user->email_verified_at) {
            return successResponse('Your account is already verified. Please login.');
        }

        $verification = VerificationCode::where('email', $email)
            ->where('code', $request->code)
            ->where('type', VerificationCode::EMAIL_VERIFICATION)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $verification) {
            return errorResponse('Invalid or expired verification code.');
        }

        $verification->update(['used_at' => now()]);

        $user->update(['email_verified_at' => now()]);

        return successResponse('verification successful.');
    }
}
