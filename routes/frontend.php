<?php

use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\SellerController;
use App\Http\Controllers\Frontend\WishlistController;
use Illuminate\Support\Facades\Route;


Route::get('{slug}/category', [CategoryController::class, 'details'])->name('category.details');

Route::get('/contact-us', [ContactUsController::class, 'contactUs'])->name('contactUs');

Route::prefix('products')->as('products.')->group(function(){
    Route::get('{slug}/details', [ProductController::class, 'details'])->name('details');
});



Route::get('/no-order', function () {
    return view('frontend.pages.no-order');
})->name('no_order');

Route::get('/tracking', function () {
    return view('frontend.pages.tracking');
})->name('tracking');



Route::prefix('sellers')->as('sellers.')->group(function () {
    Route::get('{seller:username}/shop', [SellerController::class, 'shop'])->name('shop');
    Route::post('{seller:username}/follow', [SellerController::class, 'follow'])->middleware('auth')->name('follow');
    Route::get('{seller:username}/reviews', [SellerController::class, 'review'])->name('reviews');
});

Route::middleware('auth')->group(function () {
    Route::prefix('cart')->as('cart.')->group(function () {
        Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::post('/update', [CartController::class, 'update'])->name('update');
        Route::post('/delete', [CartController::class, 'delete'])->name('delete');
        Route::get('/details', [CartController::class, 'details'])->name('details');
        Route::get('/data', [CartController::class, 'getLiveCartData'])->name('data');
    });


    Route::prefix('wishlist')->as('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/store', [WishlistController::class, 'store'])->name('store');
        Route::delete('{wishlist}/delete', [WishlistController::class, 'delete'])->name('delete');
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
