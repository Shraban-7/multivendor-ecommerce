<?php

use App\Http\Controllers\client\AuthController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::match(['get', 'post'], '/signup', [AuthController::class, 'signup'])->name('signup');
    Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/update-account', [AuthController::class, 'updateAccount'])->name('accountUpdate');
    Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('updatePassword');
});



