<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\SettingController;

 Route::get('settings', [SettingController::class, 'index']);
 Route::get('dashboard', [DashboardController::class, 'index']);

Route::middleware('guest')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    // Route::get('categories', [ContentCategoryController::class, 'index']);

    // Route::prefix('profile')->group(function () {
    //     Route::get('/', [UserController::class, 'profile']);
    //     Route::post('/', [UserController::class, 'update']);
    //     Route::post('/password', [UserController::class, 'updatePassword']);
    //     Route::post('/image', [UserController::class, 'updateImage']);
    // });
});
