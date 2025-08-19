<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/test', function () {
    return view('frontend.pages.test');
})->name('test');

Route::get('/states/{country_id}', [LocationController::class, 'getStatesByCountry'])->name('getStatesByCountry');

Route::get('/invoice/{invoice_id}', [InvoiceController::class, 'invoice'])->name('invoice');
Route::get('/receipt/{invoice_id}', [InvoiceController::class, 'receipt'])->name('receipt');

Route::get('/product', function () {
    return view('product-variant');
});

Route::prefix('payment')->as('payment.')->group(function () {
    Route::get('/pay', [PaymentController::class, 'pay'])->name('pay');
    Route::middleware('aamarpay')->group(function () {
        Route::post('/success', [PaymentController::class, 'confirm'])->name('success');
        Route::post('/cancel', [PaymentController::class, 'cancel'])->name('cancel');
        Route::post('/notify', [PaymentController::class, 'notify'])->name('notify');
    });
    Route::get('/test', function () {
        return view('payment.test');
    })->middleware('auth');
    Route::get('/mail', function () {
        return view('payment.mail');
    });
    Route::get('/manual', [PaymentController::class, 'manual']);
});
