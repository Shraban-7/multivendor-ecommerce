<?php

use App\Http\Controllers\Frontend\AuthController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ProfileController;

Route::middleware('guest')->group(function () {
    Route::match(['get', 'post'], '/signup', [AuthController::class, 'signup'])->name('signup');
    Route::match(['get', 'post'], '/seller-signup', [AuthController::class, 'sellerSignup'])->name('seller.signup');
    Route::match(['get', 'post'], '/login', [LoginController::class, 'login'])->name('login');
    Route::match(['get', 'post'], '/verify', [AuthController::class, 'verify'])->name('verify');
    Route::match(['get', 'post'], '/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.forgot');
    Route::match(['get', 'post'], '/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'profile'])->name('profile');
    Route::post('/update-account', [ProfileController::class, 'updateAccount'])->name('accountUpdate');
    Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('updatePassword');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');




