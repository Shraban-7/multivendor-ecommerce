<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\SettingController;

Route::middleware('guest')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
});

Route::get('settings', [SettingController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('dashboard', [DashboardController::class, 'index']);

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

Route::get('sellers', [SellerController::class, 'index']);
Route::get('sellers/{seller}', [SellerController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/store', [CartController::class, 'store']);
        Route::post('/items/{item}/delete', [CartController::class, 'deleteItem']);
        Route::post('/items/{item}/update-quantity', [CartController::class, 'updateQuantity']);
    });

    // Route::get('categories', [ContentCategoryController::class, 'index']);

    // Route::prefix('profile')->group(function () {
    //     Route::get('/', [UserController::class, 'profile']);
    //     Route::post('/', [UserController::class, 'update']);
    //     Route::post('/password', [UserController::class, 'updatePassword']);
    //     Route::post('/image', [UserController::class, 'updateImage']);
    // });
});
