<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\AuthController;
use App\Http\Controllers\Seller\CustomerController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\OptionController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\ProductAttributeController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\ProductVariantController;
use App\Http\Controllers\Seller\SellerCampaignController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\SettingController;

Route::middleware('seller')->prefix('seller')->as('seller.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::match(['get','post'],'/profile/{username}', [SellerController::class, 'profile'])->name('profile');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');

    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('add');
        Route::post('/store', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::get('/{slug}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::post('/{slug}/update', [ProductController::class, 'update'])->name('update');
        Route::post('/{product}/stock-update', [ProductController::class, 'stockUpdate'])->name('stockUpdate');
        Route::delete('/delete-variant/{variant}', [ProductController::class, 'deleteVariant'])->name('deleteVariant');
        Route::delete('images/{image}/delete', [ProductController::class, 'deleteImage'])->name('image.delete');

        Route::delete('/{product}/delete', [ProductController::class, 'delete'])->name('delete');
        Route::get('/get-options/{attributeId}', [ProductController::class, 'getOptions']);
    });

    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/pending', [OrderController::class, 'index'])->name('pending');
        Route::get('/shipped', [OrderController::class, 'index'])->name('shipped');
        Route::get('/delivered', [OrderController::class, 'index'])->name('delivered');
        Route::get('/cancelled', [OrderController::class, 'index'])->name('cancelled');
        Route::get('/refunded', [OrderController::class, 'index'])->name('refunded');
        Route::get('/returned', [OrderController::class, 'index'])->name('returned');
        Route::get('/details/{order}', [OrderController::class, 'details'])->name('details');
        Route::get('/invoice/{order}', [OrderController::class, 'invoice'])->name('invoice');
        Route::Post('/update-status/{order}', [OrderController::class, 'updateStatus'])->name('updateStatus');
    });

    Route::prefix('options')->as('options.')->group(function () {
        Route::post('{product}/store', [OptionController::class, 'store'])->name('store');
    });

    Route::prefix('product-variants')->as('productVariants.')->group(function () {
        Route::post('{product}/store', [ProductVariantController::class, 'store'])->name('store');
        Route::post('{product}/{variant}/update/', [ProductVariantController::class, 'update'])->name('update');
        Route::post('{variant}/delete', [ProductVariantController::class, 'destroy'])->name('delete');
    });

    Route::prefix('campaigns')->as('campaigns.')->group(function () {
        Route::get('/', [SellerCampaignController::class, 'index'])->name('index');
        Route::get('/create', [SellerCampaignController::class, 'create'])->name('create');
        Route::post('/store', [SellerCampaignController::class, 'store'])->name('store');
        Route::get('{campaign}/edit', [SellerCampaignController::class, 'edit'])->name('edit');
        Route::get('{campaign}/show', [SellerCampaignController::class, 'show'])->name('show');
        Route::post('{campaign}/add-products', [SellerCampaignController::class, 'add_products'])->name('add_products');
        Route::post('{campaign}/update', [SellerCampaignController::class, 'update'])->name('update');
        Route::post('{campaign}/delete', [SellerCampaignController::class, 'delete'])->name('delete');
    });

    Route::prefix('settings')->as('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/update', [SettingController::class, 'update'])->name('update');
    });

    Route::post('banner-image/{image}/delete', [SettingController::class, 'deleteImage'])->name('bannerImages.delete');
});


