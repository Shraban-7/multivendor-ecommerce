<?php

use App\Http\Controllers\frontend\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class,'index'])->name('home');

Route::get('/category/{slug}', [HomeController::class, 'category_details'])->name('category_details');

Route::get('/electronics', function () {
    return view('frontend.pages.electronics');
})->name('electronics');

Route::get('/product-details', function () {
    return view('frontend.pages.product_details');
})->name('product_details');

Route::get('/shop-details', function () {
    return view('frontend.pages.shop_details');
})->name('shop_details');

Route::get('/shop-review', function () {
    return view('frontend.pages.shop_review');
})->name('shop_review');

Route::get('/cart-details', function () {
    return view('frontend.pages.cart_details');
})->name('cart_details');

Route::get('/checkout', function () {
    return view('frontend.pages.checkout');
})->name('checkout');

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




