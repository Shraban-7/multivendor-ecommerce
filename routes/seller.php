<?php

use App\Http\Controllers\Seller\AuthController;
use App\Http\Controllers\Seller\CustomerController;
use App\Http\Controllers\Seller\NotificationController;
use App\Http\Controllers\Seller\PaymentListnerController;
use Illuminate\Support\Facades\Route;

Route::middleware('seller')->prefix('seller')->as('seller.')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');

    Route::prefix('payment-listener')->as('paymentListener.')->group(function () {
        Route::get('/', [PaymentListnerController::class, 'index'])->name('index');
        Route::prefix('devices')->as('devices.')->group(function () {
            Route::post('/generate-code', [PaymentListnerController::class, 'generateCode'])->name('generateCode');
            Route::delete('/{device}', [PaymentListnerController::class, 'deleteDevice'])->name('delete');
            Route::post('/{device}/check-payments', [PaymentListnerController::class, 'checkPayments'])->name('checkPayments');
        });
        Route::get('/payments', [PaymentListnerController::class, 'payments']);
    });

});
