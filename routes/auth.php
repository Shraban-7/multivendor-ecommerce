<?php

use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\Frontend\ProfileController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::match(['get', 'post'], '/signup', [AuthController::class, 'signup'])->name('signup');
    Route::match(['get', 'post'], '/seller-signup', [AuthController::class, 'sellerSignup'])->name('seller.signup');
    Route::match(['get', 'post'], '/login', [LoginController::class, 'login'])->name('login');
    Route::match(['get', 'post'], '/verify', [AuthController::class, 'verify'])->name('verify');
    Route::match(['get', 'post'], '/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.forgot');
    Route::match(['get', 'post'], '/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/verify/resend', [AuthController::class, 'resendVerification'])->name('verify.resend');
});

Route::post('/seller-signup/upload-img', [AuthController::class, 'uploadTempImage'])->name('seller.signup.uploadTempImage');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::get('/addresses', [ProfileController::class, 'addresses'])->name('addresses');
    Route::post('/update-account', [ProfileController::class, 'updateAccount'])->name('accountUpdate');
    Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('updatePassword');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
