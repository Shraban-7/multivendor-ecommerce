<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\ShopController;
use Illuminate\Support\Facades\Route;

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

Route::get('shops', [ShopController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/store', [CartController::class, 'store']);
        Route::post('/items/{item}/delete', [CartController::class, 'deleteItem']);
        Route::post('/items/{item}/update-quantity', [CartController::class, 'updateQuantity']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/store', [OrderController::class, 'store']);
        Route::get('{order}', [OrderController::class, 'show']);
        // Route::get('{invoice_id}/tracking',[OrderController::class,'tracking']);
    });

    Route::prefix('reviews')->group(function () {
        Route::post('store', [OrderController::class, 'submitReview']);
    });

    // Route::get('categories', [ContentCategoryController::class, 'index']);

    // Route::prefix('profile')->group(function () {
    //     Route::get('/', [UserController::class, 'profile']);
    //     Route::post('/', [UserController::class, 'update']);
    //     Route::post('/password', [UserController::class, 'updatePassword']);
    //     Route::post('/image', [UserController::class, 'updateImage']);
    // });

});
