<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroBannerController;
use App\Http\Controllers\Admin\HomeMidController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PromoPosterController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\SubcategoryController;

Route::middleware('admin')->prefix('admin')->as('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::prefix('customers')->as('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::post('/update', [CustomerController::class, 'update'])->name('update');
    });

    Route::prefix('sellers')->as('sellers.')->group(function () {
        Route::get('/', [SellerController::class, 'index'])->name('index');
        Route::get('/update', [SellerController::class, 'update'])->name('update');
    });

    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
    });

    Route::prefix('categories')->as('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{category}', [CategoryController::class, 'update'])->name('update');
        Route::post('/toggle-status/{category}', [CategoryController::class, 'toggleStatus'])->name('toggleStatus');
    });

    Route::prefix('subcategories')->as('subcategories.')->group(function () {
        Route::get('/', [SubcategoryController::class, 'index'])->name('index');
        Route::get('/create', [SubcategoryController::class, 'create'])->name('create');
        Route::post('/store', [SubcategoryController::class, 'store'])->name('store');
        Route::get('{subcategory}/edit', [SubcategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{subcategory}', [SubcategoryController::class, 'update'])->name('update');
        Route::post('/toggle-status/{subcategory}', [SubcategoryController::class, 'toggleStatus'])->name('toggleStatus');
    });

    Route::prefix('settings')->as('settings.')->group(function(){
        Route::prefix('hero')->as('hero.')->group(function(){
            Route::get('/',[HeroBannerController::class,'index'])->name('index');
            Route::post('/store',[HeroBannerController::class,'store'])->name('store');
            Route::post('/update/{heroBanner}',[HeroBannerController::class,'update'])->name('update');
        });

        Route::prefix('banners')->as('banners.')->group(function(){
            Route::get('/',[HomeMidController::class,'index'])->name('index');
            Route::post('/store',[HomeMidController::class,'store'])->name('store');
            Route::post('/update/{banner}',[HomeMidController::class,'update'])->name('update');
        });

        Route::prefix('posters')->as('posters.')->group(function(){
            Route::get('/',[PromoPosterController::class,'index'])->name('index');
            Route::post('/store',[PromoPosterController::class,'store'])->name('store');
            Route::post('/update/{poster}',[PromoPosterController::class,'update'])->name('update');
        });

        Route::prefix('social-links')->as('socialLinks.')->group(function(){
            Route::get('/',[SocialLinkController::class,'index'])->name('index');
            Route::post('/store',[SocialLinkController::class,'store'])->name('store');
            Route::post('/update/{socialLink}',[SocialLinkController::class,'update'])->name('update');
        });

        Route::prefix('payment-gateways')->as('paymentGateways.')->group(function(){
            Route::get('/',[PaymentGatewayController::class,'index'])->name('index');
            Route::post('/store',[PaymentGatewayController::class,'store'])->name('store');
            Route::post('/update/{gateway}',[PaymentGatewayController::class,'update'])->name('update');
        });
    });
});

Route::middleware('guest')->prefix('admin')->as('admin.')->group(function () {
    Route::match(['get', 'post'], '/signup', [AuthController::class, 'signup'])->name('signup');
    Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
});
