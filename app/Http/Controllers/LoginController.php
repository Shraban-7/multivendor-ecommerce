<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Seller;
use App\Models\SellerEmployee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        if ($request->isMethod('GET')) {
            return view('frontend.auth.login');
        }

        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $userTypes = [
            'user' => [
                'model' => \App\Models\User::class,
                'guard' => 'web',
                'redirect' => route('home'),
                //'check' => fn($user) => !is_null($user->email_verified_at),
                'check' => fn($user) => true
            ],
            'seller' => [
                'model' => \App\Models\Seller::class,
                'guard' => 'seller',
                'redirect' => route('seller.dashboard'),
                'check' => fn($seller) => $seller->status == Seller::ACTIVE,
                'inactiveMessage' => "Your account is inactive, contact with admin",
            ],
            'employee' => [
                'model' => \App\Models\SellerEmployee::class,
                'guard' => 'employee',
                'redirect' => route('seller.pos.index'),
                'check' => fn($employee) => $employee->is_active == 1,
                'inactiveMessage' => 'Your account is inactive, contact with seller',
            ],
            'admin' => [
                'model' => \App\Models\Admin::class,
                'guard' => 'admin',
                'redirect' => route('admin.dashboard'),
                'check' => fn($admin) => true,
            ],
        ];

        foreach ($userTypes as $type => $config) {
            $model = $config['model'];
            $user = $model::where('email', $request->email)->first();

            if (! $user) {
                continue;
            }

            if ($type === 'seller') {
                if ($user->status == Seller::BLOCKED) {
                    return redirect()->back()->with('warning', 'Your account has been blocked. Contact admin.');
                }

                if ($user->status != Seller::ACTIVE) {
                    return redirect()->back()->with('warning', 'Your account is pending approval. Please wait for admin review.');
                }
            } elseif (! ($config['check'])($user)) {
                return redirect()->back()->with('warning', $config['inactiveMessage'] ?? 'Account inactive');
            }

            if (! Auth::guard($config['guard'])->attempt($credentials)) {
                return redirect()->back()->with('error', 'Incorrect password!');
            }

            $request->session()->regenerate();

            session()->flash('success', 'Login successful');

            return redirect()->intended($config['redirect']);
        }

        return redirect()->back()->with('error', 'Incorrect email!');
    }

    // public function loginOld(Request $request)
    // {
    //     if ($request->isMethod('GET')) {
    //         return view('frontend.auth.login');
    //     }

    //     $user   = User::where('email', $request->email)->first();
    //     $seller = Seller::where('email', $request->email)->first();
    //     $admin  = Admin::where('email', $request->email)->first();
    //     $employee = SellerEmployee::where('email', $request->email)->first();

    //     if (! $user && ! $seller && ! $admin && !$employee) {
    //         return redirect()->back()->with('error', 'Incorrect email!');
    //     }

    //     if ($user) {
    //         session(['url.intended' => url()->previous()]);
    //         if (! Auth::attempt($request->only('email', 'password'))) {
    //             return redirect()->back()->with('error', 'Incorrect password!');
    //         }

    //         $request->session()->regenerate();

    //         return redirect()->intended(route('home'));
    //     }

    //     if ($seller) {
    //         if ($seller->status == Seller::BLOCKED) {
    //             return redirect()->back()->with('error', 'Your account has been blocked by admin');
    //         }
    //         if ($seller->status == Seller::PENDING) {
    //             return redirect()->back()->with('warning', 'Wait for admin approval');
    //         }

    //         if (! Auth::guard('seller')->attempt($request->only('email', 'password'))) {
    //             return redirect()->back()->with('error', 'Incorrect password!');
    //         }

    //         $request->session()->regenerate();
    //         session()->flash('success', 'Login successful');

    //         return redirect()->route('seller.dashboard')->with('success', 'You successfully login');
    //     }

    //     if ($employee) {
    //         if ($employee->is_active != 1) {
    //             return redirect()->back()->with('warning', 'Wait for admin approval');
    //         }

    //         if (! Auth::guard('employee')->attempt($request->only('email', 'password'))) {
    //             return redirect()->back()->with('error', 'Incorrect password!');
    //         }

    //         $request->session()->regenerate();
    //         session()->flash('success', 'Login successful');

    //         return redirect()->route('seller.pos.index')->with('success', 'You successfully login');
    //     }

    //     if ($admin) {
    //         if (! Auth::guard('admin')->attempt($request->only('email', 'password'))) {
    //             return redirect()->back()->with('error', 'Incorrect password!');
    //         }

    //         $request->session()->regenerate();
    //         session()->flash('success', 'Login successful');

    //         return redirect()->route('admin.dashboard');
    //     }

    //     return redirect()->back()->with('error', 'Something went wrong.')->with('success', 'You successfully login');
    // }
}
