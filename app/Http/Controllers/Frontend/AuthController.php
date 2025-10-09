<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Seller;
use App\Enums\UserRole;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Models\VerificationCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('frontend.auth.signup');
        }

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'phone'    => 'required|string|max:20',
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
            'user_id'    => $user->id,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'code'       => $code,
            'type'       => 'email_verification',
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        $request->session()->put('verify_email', $user->email);

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
                $data = $request->validate([
                    'name' => 'required|string|max:255',

                    'email' => 'required|string|email|max:255|unique:sellers,email',
                    'phone' => 'required|string|max:200',
                    'nid_no' => 'required|string|max:50',
                    'password' => 'required|string|min:5|confirmed',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
                    'nid_front_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
                    'nid_back_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif,svg|max:12288',
                ]);

                $sessionData = $request->except(['image', 'nid_front_image', 'nid_back_image']);
                session(['seller_step1' => $sessionData]);

                return apiResponse(['next_step' => 2], 'Step 1 complete');

            case 2:
                $data = $request->validate([
                    'business_name' => 'required|string|max:255',
                    'business_email' => 'required|string|email|max:255|unique:sellers,business_email',
                    'business_address' => 'required|string|max:1000',
                    'division_id' => 'required|exists:divisions,id',
                    'district_id' => 'required|exists:districts,id',
                ]);

                $sessionData = $request->except(['business_logo']);

                session(['seller_step2' => $sessionData]);
                return apiResponse(['next_step' => 3], 'Step 2 complete');

            case 3:
                $data = $request->validate([
                    'trade_license_no' => 'required|string|max:100',

                ]);

                $sessionData = $request->except(['trade_license_image', 'shop_image']);

                $step1 = session('seller_step1', []);
                $step2 = session('seller_step2', []);
                $allData = array_merge($step1, $step2, $sessionData);

                $allData['username'] = str_slug('sellers', 'username', $allData['name']);
                $username = $allData['username'];

                $imageFields = [
                    'image'               => "images/{$username}/avatar",
                    'nid_front_image'     => "images/{$username}/nids",
                    'nid_back_image'      => "images/{$username}/nids",
                    'business_logo'       => "images/{$username}/logo",
                    'trade_license_image' => "images/{$username}/licenses",
                    'shop_image'          => "images/{$username}/shops",
                ];

                foreach ($imageFields as $field => $folder) {
                    if ($request->hasFile($field)) {
                        $allData[$field] = upload_file($request->file($field), $folder);
                    }
                }

                Seller::create($allData);

                session()->forget(['seller_step1', 'seller_step2']);

                return successResponse('Your registration is complete');
        }

        return errorResponse('Invalid step');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('seller')->check()) {
            Auth::guard('seller')->logout();

            return redirect()->route('login');
        }

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();

            return redirect()->route('login');
        }

        return redirect()->route('login');
    }

    public function verify(Request $request)
    {
        $email = $request->session()->get('verify_email');

        if (!$email) {
            return redirect()->route('login')->with('info', 'Please register first.');
        }

        $user = User::where('email', $email)->first();

        if ($user && $user->email_verified_at) {
            $request->session()->forget('verify_email');

            return redirect()->route('login')->with('success', 'Your account is already verified. Please login.');
        }

        if ($request->isMethod('GET')) {
            $settings = settings();
            return view('frontend.auth.verify', compact('settings', 'email'));
        }

        $request->validate([
            'code' => 'required|string|max:6',
        ]);

        $verification = VerificationCode::where('email', $email)
            ->where('code', strtoupper($request->code))
            ->whereNull('used_at')
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$verification) {
            return back()->withErrors(['code' => 'Invalid or expired verification code.']);
        }

        $verification->update(['used_at' => now()]);

        $user->update(['email_verified_at' => now()]);

        $request->session()->forget('verify_email');

        return redirect()->route('login')->with('success', 'Your account has been verified successfully!');
    }
}
