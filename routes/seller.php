<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\AuthController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\ProductController;

Route::middleware('seller')->prefix('seller')->as('seller.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/shop-details/{username}', [DashboardController::class, 'shop_details'])->name('shop_details');
});

Route::middleware('guest')->prefix('seller')->as('seller.')->group(function () {
    Route::match(['get', 'post'], '/signup', [AuthController::class, 'signup'])->name('signup');
    Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');

    Route::get('/products', [ProductController::class, 'products'])->name('products');

    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/pending', [OrderController::class, 'orders'])->name('pending');
        Route::get('/shipped', [OrderController::class, 'orders'])->name('shipped');        
        Route::get('/delivered', [OrderController::class, 'orders'])->name('delivered');
        Route::get('/cancelled', [OrderController::class, 'orders'])->name('cancelled');
    });

});
