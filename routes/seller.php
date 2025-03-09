<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\AuthController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\SellerController;

Route::middleware('seller')->prefix('seller')->as('seller.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::match(['get','post'],'/profile/{username}', [SellerController::class, 'profile'])->name('profile');
    Route::get('/shop-details/{username}', [SellerController::class, 'shop_details'])->name('shop_details');

    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('add');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}/details', [ProductController::class, 'details'])->name('details');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::post('/{product}/update', [ProductController::class, 'update'])->name('update');

        Route::delete('images/{image}/delete', [ProductController::class, 'deleteImage'])->name('image.delete');

        Route::delete('/{product}/delete', [ProductController::class, 'delete'])->name('delete');
    });

    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/pending', [OrderController::class, 'index'])->name('pending');
        Route::get('/shipped', [OrderController::class, 'index'])->name('shipped');
        Route::get('/delivered', [OrderController::class, 'index'])->name('delivered');
        Route::get('/cancelled', [OrderController::class, 'index'])->name('cancelled');
        Route::get('/details/{order}', [OrderController::class, 'details'])->name('details');
        Route::Post('/update-status/{order}', [OrderController::class, 'updateStatus'])->name('updateStatus');
    });
});

Route::middleware('guest')->prefix('seller')->as('seller.')->group(function () {
    Route::match(['get', 'post'], '/signup', [AuthController::class, 'signup'])->name('signup');
    Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
});
