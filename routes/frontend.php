<?php

use App\Http\Controllers\Frontend\AffiliatorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\SellerController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Frontend\NotificationController;
use App\Http\Controllers\Frontend\BillingAddressController;

Route::get('categories/{slug}', [CategoryController::class, 'details'])->name('category.details');

Route::get('/contact-us', [ContactUsController::class, 'contactUs'])->name('contactUs');

Route::prefix('products')->as('products.')->group(function () {
    Route::get('{slug}', [ProductController::class, 'details'])->name('details');
    Route::post('{slug}/get-variant', [ProductController::class, 'getVariant']);
});

Route::get('/no-order', function () {
    return view('frontend.pages.no-order');
})->name('no_order');

Route::get('/tracking', function () {
    return view('frontend.pages.tracking');
})->name('tracking');

Route::prefix('sellers')->as('sellers.')->group(function () {
    Route::get('/', [SellerController::class, 'index'])->name('index');
    Route::get('{seller:username}', [SellerController::class, 'shop'])->name('shop');
    Route::post('{seller:username}/follow', [SellerController::class, 'follow'])->middleware('auth')->name('follow');
    Route::get('{seller:username}/reviews', [SellerController::class, 'review'])->name('reviews');
    Route::post('/reviews/{review}/helpful', [SellerController::class, 'markHelpful'])->name('reviews.helpful');
    Route::post('/reviews/report', [SellerController::class, 'reviewReport'])->name('reviews.report');
});

Route::prefix('campaigns')->as('campaigns.')->group(function () {
    Route::get('{slug}/products', [SellerController::class, 'campaign_products'])->name('campaign_products');
});

Route::get('/get-districts/{divisionId}', [OrderController::class, 'getDistricts']);


Route::middleware('auth')->group(function () {

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware('markReadAuto');

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
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::match(['get', 'post'], 'checkout', [OrderController::class, 'checkout'])->name('checkout');
        Route::post('{order}/pay-now', [OrderController::class, 'payNow'])->name('payNow');
        Route::get('/details/{invoice_id}', [OrderController::class, 'details'])->name('details');
        Route::get('/success/{invoice_id}', [OrderController::class, 'success'])->name('success');
        Route::get('/tracking/{invoice_id}', [OrderController::class, 'tracking'])->name('tracking');
        Route::match(['get', 'post'], '/review', [OrderController::class, 'review'])->name('review');
    });

    Route::prefix('billing-addresses')->as('billing_addresses.')->group(function () {
        Route::post('/store', [BillingAddressController::class, 'store'])->name('store');
        Route::post('/{address}/update', [BillingAddressController::class, 'update'])->name('update');
    });

    Route::prefix('affiliator')->as('affiliator.')->group(function () {
        Route::get('/dashboard', [AffiliatorController::class, 'dashboard'])->name('dashboard');
        Route::match(['get','post'],'/withdraw',[AffiliatorController::class, 'withdraw'])->name('withdraw');
    });
});
