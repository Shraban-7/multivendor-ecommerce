<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BillingAddressController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SellerController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DataController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\SellerChatController;
use App\Http\Controllers\Seller\PaymentListnerController;

Route::prefix('auth')->group(function () {
    Route::prefix('password-reset')->group(function () {
        Route::post('send-code', [AuthController::class, 'sendResetCode']);
        Route::post('verify-code', [AuthController::class, 'verifyResetCode']);
        Route::post('set-password', [AuthController::class, 'setPassword']);
    });

    Route::prefix('email-verification')->group(function () {
        Route::post('resend-code', [AuthController::class, 'resendCode']);
        Route::post('verify-code', [AuthController::class, 'verifyEmailCode']);
    });
});

Route::middleware('guest')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('signup', [AuthController::class, 'signup']);
});

Route::get('settings', [SettingController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);
Route::get('dashboard', [DashboardController::class, 'index']);
Route::post('search', [SearchController::class, 'search']);

Route::get('products', [ProductController::class, 'index']);
Route::get('products/{product}', [ProductController::class, 'show']);

Route::get('sellers', [SellerController::class, 'index']);
Route::get('sellers/{seller}', [SellerController::class, 'show']);

Route::get('shops', [ShopController::class, 'index']);

Route::prefix('data')->group(function () {
    Route::get('/divisions', [DataController::class, 'divisions']);
    Route::get('/districts', [DataController::class, 'districts']);
    Route::get('/payment-gateways', [DataController::class, 'paymentGateways']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('/notifications/count', [NotificationController::class, 'notificationCount']);
    Route::get('/notifications', [NotificationController::class, 'index'])->middleware('markReadAuto');

    Route::prefix('chat')->group(function () {
        Route::get('/messages', [SellerChatController::class, 'messages']);
        Route::post('/send', [SellerChatController::class, 'sendMessage']);
    });

    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::get('/suggestions', [CartController::class, 'suggestions']);
        Route::post('/store', [CartController::class, 'store']);
        Route::post('/items/{item}/delete', [CartController::class, 'deleteItem']);
        Route::post('/items/{item}/update-quantity', [CartController::class, 'updateQuantity']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/store', [OrderController::class, 'store']);
        Route::get('{order}', [OrderController::class, 'show']);
        Route::get('{order}/invoice', [OrderController::class, 'invoice']);
        Route::post('{order}/pay-now', [OrderController::class, 'payNow']);
        // Route::get('{invoice_id}/tracking',[OrderController::class,'tracking']);
    });

    Route::prefix('reviews')->group(function () {
        Route::post('store', [OrderController::class, 'submitReview']);
    });

    // Route::get('categories', [ContentCategoryController::class, 'index']);

    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'profile']);
        Route::post('/', [ProfileController::class, 'update']);
    });

    Route::prefix('billing-addresses')->group(function () {
        Route::get('/', [BillingAddressController::class, 'index']);
        Route::post('/store', [BillingAddressController::class, 'store']);
        Route::post('/{address}/update', [BillingAddressController::class, 'update']);
    });
});

Route::prefix('payment-listener')->group(function () {
    Route::get('/connect', [PaymentListnerController::class, 'connectDevice']);
    Route::get('/disconnect', [PaymentListnerController::class, 'disconnectDevice']);    
    Route::get('/trigger', [PaymentListnerController::class, 'triggerSms']);    
});