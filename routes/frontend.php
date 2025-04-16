<?php

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\SellerController;
use Illuminate\Support\Facades\Route;


Route::get('/category/{slug}', [CategoryController::class, 'category_details'])->name('category_details');

Route::get('/contact-us', [ContactUsController::class, 'contactUs'])->name('contactUs');

Route::get('/product-details/{slug}', [ProductController::class, 'details'])->name('product.details');

Route::get('/shop-review', function () {
    return view('frontend.shops.review');
})->name('shop_review');

Route::get('/no-order', function () {
    return view('frontend.pages.no_order');
})->name('no_order');

Route::get('/tracking', function () {
    return view('frontend.pages.tracking');
})->name('tracking');

Route::get('/wishlist', function () {
    return view('frontend.pages.wishlist');
})->name('wishlist');

Route::prefix('sellers')->as('sellers.')->group(function () {
    Route::get('{seller:username}/shop', [SellerController::class, 'shop'])->name('shop');
    Route::post('{seller:username}/follow', [SellerController::class, 'follow'])->middleware('auth')->name('follow');
});

Route::middleware('auth')->group(function () {
    Route::prefix('cart')->as('cart.')->group(function () {
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::post('/update', [CartController::class, 'update'])->name('update');
        Route::post('/delete', [CartController::class, 'delete'])->name('delete');
        Route::get('/details', [CartController::class, 'details'])->name('details');
        Route::get('/data', [CartController::class, 'getLiveCartData'])->name('data');
    });

    Route::prefix('orders')->as('orders.')->group(function () {
        Route::match(['get', 'post'], 'checkout/', [OrderController::class, 'checkout'])->name('checkout');
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/details/{order}', [OrderController::class, 'details'])->name('details');
        Route::get('/success/{order}', [OrderController::class, 'success'])->name('success');
        Route::get('/tracking/{invoice_id}', [OrderController::class, 'tracking'])->name('tracking');
        Route::match(['get', 'post'], '/review/{order}', [OrderController::class, 'review'])->name('review');
    });


});
