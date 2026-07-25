<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ShopController;
use Illuminate\Support\Facades\Route;

// Route::prefix('auth')->group(function () {
//     Route::prefix('password-reset')->group(function () {
//         Route::post('send-code', [AuthController::class, 'sendResetCode']);
//         Route::post('verify-code', [AuthController::class, 'verifyResetCode']);
//         Route::post('set-password', [AuthController::class, 'setPassword']);
//     });

//     Route::prefix('email-verification')->group(function () {
//         Route::post('resend-code', [AuthController::class, 'resendCode']);
//         Route::post('verify-code', [AuthController::class, 'verifyEmailCode']);
//     });
// });

Route::prefix('auth')->group(function () {
    Route::post('check-phone', [AuthController::class, 'checkPhone'])->middleware('throttle:5,1');
    Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:5,1');
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:10,1');
});

// Route::middleware('guest')->group(function () {
//     Route::post('login', [AuthController::class, 'login']);
//     Route::post('signup', [AuthController::class, 'signup']);
// });

Route::get('settings', [SettingController::class, 'index']);
Route::get('dashboard', [DashboardController::class, 'index']);
Route::post('search', [SearchController::class, 'search'])->middleware('throttle:30,1');

Route::get('shops', [ShopController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('/notifications/count', [NotificationController::class, 'notificationCount']);
    Route::get('/notifications', [NotificationController::class, 'index'])->middleware('markReadAuto');

    // Route::get('categories', [ContentCategoryController::class, 'index']);

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'profile']);
        Route::post('/', [ProfileController::class, 'update']);
    });
});
