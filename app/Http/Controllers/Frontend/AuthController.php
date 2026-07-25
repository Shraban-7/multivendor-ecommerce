<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Shipping\Models\Division;
use App\Domain\Vendor\Models\Seller;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Mail\Vendor\RegistrationPendingMail;
use App\Models\User;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('frontend.auth.signup');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:5|confirmed',
        ]);

        $data['username'] = str_slug('users', 'username', $data['name']);

        if ($request->has('role') && $request->role === UserRole::AFFILIATE->label()) {
            $data['role'] = UserRole::AFFILIATE->value;
        } else {
            $data['role'] = UserRole::CUSTOMER->value;
        }

        $user = User::create($data);

        $code = VerificationCode::generateCode();

        VerificationCode::create([
            'user_id' => $user->id,
            'email' => $user->email,
            'phone' => $user->phone,
            'code' => $code,
            'type' => VerificationCode::EMAIL_VERIFICATION,
            'expires_at' => Carbon::now()->addMinutes(VerificationCode::EXPIRY_MINUTES),
        ]);

        $request->session()->put('verify_email', $user->email);

        $user->sendEmailVerificationMail();

        return redirect()->route('verify')
            ->with('success', 'Signup successful! Please check your email for a verification code.');
    }

    public function sellerSignup(Request $request)
    {
        $divisions = Division::all();

        if ($request->isMethod('GET')) {
            return view('frontend.auth.seller-signup', compact('divisions'));
        }

        $step = $request->input('step', 1);

        switch ($step) {
            case 1:
                $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|string|email|max:255|unique:sellers,email',
                    'phone' => 'required|string|max:200',
                    'nid_no' => 'required|string|max:50',
                    'password' => 'required|string|min:5|confirmed',
                ]);

                $sessionData = $request->except(['image', 'nid_front_image', 'nid_back_image']);

                if (! session()->has('image')) {
                    return errorResponse('The image field is required!');
                }
                if (! session()->has('nid_front_image')) {
                    return errorResponse('NID front image is required!');
                }
                if (! session()->has('nid_back_image')) {
                    return errorResponse('NID back image is required!');
                }

                session(['seller_step1' => $sessionData]);

                return apiResponse(['next_step' => 2], 'Step 1 complete');

            case 2:
                $request->validate([
                    'business_name' => 'required|string|max:255',
                    'business_email' => 'required|string|email|max:255|unique:sellers,business_email',
                    'business_address' => 'required|string|max:1000',
                    'division_id' => 'required|exists:divisions,id',
                    'district_id' => 'required|exists:districts,id',
                ]);

                $sessionData = $request->except(['business_logo']);

                session(['seller_step2' => $sessionData]);

                if (! session()->has('business_logo')) {
                    return errorResponse('Business logo is required!');
                }

                return apiResponse(['next_step' => 3], 'Step 2 complete');

            case 3:
                $request->validate([
                    'trade_license_no' => 'required|string|max:100',
                ]);

                if (! session()->has('trade_license_image')) {
                    return errorResponse('Trade license image is required!');
                }
                if (! session()->has('shop_image')) {
                    return errorResponse('Shop image is required!');
                }

                $sessionData = $request->except(['trade_license_image', 'shop_image']);

                $step1 = session('seller_step1', []);
                $step2 = session('seller_step2', []);
                $allData = array_merge($step1, $step2, $sessionData);

                $allData['username'] = str_slug('sellers', 'username', $allData['name']);
                $allData['code'] = Seller::generateSellerCode($allData['name']);
                $username = $allData['username'];

                $destinationDir = $username;
                if (! Storage::disk('public')->exists($destinationDir)) {
                    Storage::disk('public')->makeDirectory($destinationDir);
                }

                $imageData = [
                    'image' => $this->moveTempImage(session('image'), $destinationDir),
                    'nid_front_image' => $this->moveTempImage(session('nid_front_image'), $destinationDir),
                    'nid_back_image' => $this->moveTempImage(session('nid_back_image'), $destinationDir),
                    'business_logo' => $this->moveTempImage(session('business_logo'), $destinationDir),
                    'trade_license_image' => $this->moveTempImage(session('trade_license_image'), $destinationDir),
                    'shop_image' => $this->moveTempImage(session('shop_image'), $destinationDir),
                ];

                $allData = array_merge($allData, $imageData);

                try {
                    $seller = Seller::create($allData);

                    session()->forget([
                        'seller_step1',
                        'seller_step2',
                        'image',
                        'nid_front_image',
                        'nid_back_image',
                        'business_logo',
                        'trade_license_image',
                        'shop_image',
                    ]);

                    $request->session()->put('verify_email', $seller->email);

                    session()->flash('message_data', [
                        'title' => 'Thank You for Registering!',
                        'message' => 'Your seller account is under review. We’ve sent a confirmation email.',
                        'buttonText' => 'Go to Home',
                        'buttonUrl' => route('home'),
                        'type' => 'success',
                    ]);

                    // Mail::to($seller->email)->queue(new RegistrationPendingMail($seller->business_name));

                    return successResponse('Registration is complete, check your email.');
                } catch (\Throwable $e) {

                    return errorResponse($e->getMessage());
                }
        }

        return errorResponse('Invalid step');
    }

    private function moveTempImage($imagePath, $destinationDir)
    {
        if (! Storage::disk('public')->exists($imagePath)) {
            return null;
        }

        $filename = basename($imagePath);
        $newPath = $destinationDir.'/'.$filename;

        Storage::disk('public')->move($imagePath, $newPath);

        return $newPath;
    }

    public function uploadTempImage(Request $request)
    {
        $allowedNames = ['image', 'nid_front_image', 'nid_back_image', 'trade_license_image', 'business_logo', 'shop_image'];

        $request->validate([
            'name' => 'required|string|in:'.implode(',', $allowedNames),
            'image' => 'required|image|mimes:jpeg,png,jpg|max:8000',
        ]);

        if ($alreadyUploaded = session()->get($request->name)) {
            delete_file($alreadyUploaded);
        }

        $image = upload_file($request->file('image'), 'images/temp');

        session()->put($request->name, $image);

        return successResponse('Image uploaded successfully');
    }

    private function logMemoryUsage($source)
    {
        $usage = memory_get_usage(true);
        $peak = memory_get_peak_usage(true);

        \Log::info("Current memory usage from {$source}: ".$this->formatBytes($usage));
        \Log::info("Peak memory usage from {$source}: ".$this->formatBytes($peak));
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision).' '.$units[$pow];
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        if (Auth::guard('seller')->check()) {
            Auth::guard('seller')->logout();
        }

        if (Auth::guard('employee')->check()) {
            Auth::guard('employee')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    public function forgotPassword(Request $request)
    {
        $settings = settings();

        if ($request->isMethod('GET')) {
            return view('frontend.auth.forgot-password', compact('settings'));
        }

        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        $account = User::where('email', $email)->first() ?? Seller::where('email', $email)->first();

        if (! $account) {
            return back()->with('error', 'No account found with this email.')->withInput();
        }

        $code = VerificationCode::generateCode();

        VerificationCode::where('email', $email)
            ->where('type', VerificationCode::PASSWORD_RESET)
            ->whereNull('used_at')
            ->delete();

        VerificationCode::create([
            'email' => $email,
            'phone' => $account->phone ?? null,
            'code' => $code,
            'type' => VerificationCode::PASSWORD_RESET,
            'expires_at' => now()->addMinutes(VerificationCode::EXPIRY_MINUTES),
        ]);

        $request->session()->put('reset_email', $email);

        return redirect()->route('password.reset');
    }

    public function resetPassword(Request $request)
    {
        $settings = settings();
        $email = $request->session()->get('reset_email');

        if (! $email) {
            return redirect()->route('password.forgot')->with('info', 'Please enter your email first.');
        }

        if ($request->isMethod('GET')) {
            return view('frontend.auth.reset-password', compact('email', 'settings'));
        }

        $request->validate([
            'verification_code' => 'required|string|max:6',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $verification = VerificationCode::where('email', $email)
            ->where('code', $request->verification_code)
            ->where('type', VerificationCode::PASSWORD_RESET)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (! $verification) {
            return back()->with('error', 'Invalid or expired verification code.');
        }

        $verification->update(['used_at' => now()]);

        $account = User::where('email', $email)->first() ?? Seller::where('email', $email)->first();

        if (! $account) {
            return redirect()->route('password.forgot')->with('error', 'Account not found.');
        }

        $account->update([
            'password' => Hash::make($request->password),
        ]);

        $request->session()->forget('reset_email');

        return redirect()->route('home')->with('success', 'Password reset successfully!');
    }

    public function verify(Request $request)
    {
        $settings = settings();
        $email = $request->session()->get('verify_email');

        if (! $email) {
            return redirect()->route('home')->with('info', 'Please register first.');
        }

        $user = User::where('email', $email)->first() ?? Seller::where('email', $email)->first();
        if (! $user) {
            $request->session()->forget('verify_email');

            return redirect()->route('home')->with('info', 'Please register first.');
        }

        if ($user->email_verified_at) {
            $request->session()->forget('verify_email');

            return redirect()->route('home')->with('success', 'Your account is already verified. Please login.');
        }

        $recent = VerificationCode::where('email', $email)
            ->where('type', VerificationCode::EMAIL_VERIFICATION)
            ->latest()
            ->first();

        $resendSeconds = 0;
        if ($recent) {
            $expiresAt = $recent->created_at->addMinutes(VerificationCode::EXPIRY_MINUTES);
            $diff = $expiresAt->diffInSeconds(now(), false);
            $resendSeconds = $diff > 0 ? $diff : 0;
        }

        if ($request->isMethod('GET')) {
            return view('frontend.auth.verify', compact('settings', 'email', 'resendSeconds'));
        }

        $request->validate([
            'code' => 'required|string|max:6',
        ]);

        $verification = VerificationCode::where('email', $email)
            ->where('code', $request->code)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();

        if (! $verification) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $verification->update(['used_at' => now()]);

        $user->update(['email_verified_at' => now()]);

        $user->sendWelcomeMail();

        $request->session()->forget('verify_email');

        return redirect()->route('home')->with('success', 'Your account has been verified successfully!');
    }

    public function resendVerification(Request $request)
    {
        $email = $request->session()->get('verify_email') ?? $request->email;

        if (! $email) {
            return errorResponse('Email not found. Please sign up first.');
        }

        $account = User::where('email', $email)->first() ?? Seller::where('email', $email)->first();

        if (! $account) {
            return errorResponse('No account found with this email.');
        }

        $lastSent = $request->session()->get('last_resend_time', null);
        $secondsPassed = $lastSent ? now()->diffInSeconds($lastSent) : 999;

        if ($secondsPassed < 120) {
            return apiResponse(['resend_seconds' => 120 - $secondsPassed], 'Please wait before requesting a new code.', 429);
        }

        $code = VerificationCode::generateCode();

        VerificationCode::create([
            'email' => $email,
            'code' => $code,
            'type' => VerificationCode::EMAIL_VERIFICATION,
            'expires_at' => now()->addMinutes(VerificationCode::EXPIRY_MINUTES),
        ]);

        $request->session()->put('last_resend_time', now());

        return apiResponse(['resend_seconds' => 120], 'A new verification code has been sent to your email.');
    }
}
