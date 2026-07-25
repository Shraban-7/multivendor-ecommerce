<?php

use App\Http\Controllers\Frontend\AffiliatorController;
use App\Http\Controllers\Frontend\BillingAddressController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Frontend\NotificationController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\StaticPageController;
use App\Http\Controllers\Frontend\WishlistController;
use Illuminate\Support\Facades\Route;

Route::get('pages/{slug}', [StaticPageController::class, 'show'])->name('pages.show');

Route::get('/contact-us', [ContactUsController::class, 'contactUs'])->name('contactUs');

Route::get('/no-order', function () {
    return view('frontend.pages.no-order');
})->name('no_order');

Route::get('/tracking', function () {
    return view('frontend.orders.status_logs');
})->name('tracking');

Route::get('/get-districts/{divisionId}', [OrderController::class, 'getDistricts']);

Route::post('cart/add', [CartController::class, 'add'])->name('cart.add');

Route::middleware('auth')->group(function () {

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware('markReadAuto');

    Route::prefix('cart')->as('cart.')->group(function () {
        // Route::post('/add', [CartController::class, 'add'])->name('add');
        Route::post('/update', [CartController::class, 'update'])->name('update');
        Route::post('/delete', [CartController::class, 'delete'])->name('delete');
        Route::get('/details', [CartController::class, 'details'])->name('details');
        Route::get('/data', [CartController::class, 'getLiveCartData'])->name('data');
    });

    Route::prefix('wishlist')->as('wishlist.')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('index');
        Route::post('/store', [WishlistController::class, 'store'])->name('store');
        Route::delete('{wishlist}/delete', [WishlistController::class, 'delete'])->name('delete');
        Route::get('/data', [WishlistController::class, 'getLiveWishlistData'])->name('data');
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
        Route::match(['get', 'post'], '/withdraw', [AffiliatorController::class, 'withdraw'])->name('withdraw');
    });
});

Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

Route::get('/message', function () {
    if (! session()->has('message_data')) {
        return redirect()->route('home');
    }

    return view('frontend.pages.message', session('message_data'));
})->name('frontend.message');
