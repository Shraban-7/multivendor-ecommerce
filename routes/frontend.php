<?php

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ProductController;
use Illuminate\Support\Facades\Route;


Route::get('/category/{slug}', [CategoryController::class, 'category_details'])->name('category_details');

Route::get('/product-details/{slug}', [ProductController::class, 'details'])->name('product.details');

Route::get('/shop-review', function () {
    return view('frontend.pages.shop_review');
})->name('shop_review');

Route::get('/no-order', function () {
    return view('frontend.pages.no_order');
})->name('no_order');

Route::get('/order-success', function () {
    return view('frontend.pages.order_success');
})->name('order_success');

Route::get('/tracking', function () {
    return view('frontend.pages.tracking');
})->name('tracking');

Route::get('/tracking-order', function () {
    return view('frontend.pages.tracking_order');
})->name('tracking_order');

Route::get('/wishlist', function () {
    return view('frontend.pages.wishlist');
})->name('wishlist');


Route::middleware('auth')->group(function () {
    Route::prefix('cart')->as('cart.')->group(function () {
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::post('/update', [CartController::class, 'update'])->name('update');
        Route::post('/delete', [CartController::class, 'delete'])->name('delete');
        Route::get('/details', [CartController::class, 'details'])->name('details');
    });

    Route::match(['get', 'post'], 'checkout/', [CheckoutController::class, 'checkout'])->name('checkout');
});
