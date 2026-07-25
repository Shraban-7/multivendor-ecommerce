<?php

use App\Domain\Payment\Http\Controllers\Admin\ManualPaymentMethodController;
use App\Domain\Payment\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Domain\Payment\Http\Controllers\Admin\PaymentGatewayController;
use App\Domain\Payment\Http\Controllers\Admin\PaymentOptionController;
use App\Domain\Payment\Http\Controllers\Api\DataController;
use App\Domain\Payment\Http\Controllers\Api\MobipayController;
use App\Domain\Payment\Http\Controllers\BkashController;
use App\Domain\Payment\Http\Controllers\PaymentController;
use App\Domain\Payment\Http\Controllers\Seller\PaymentListnerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::prefix('payments')->as('payments.')->group(function () {
        Route::get('/', [AdminPaymentController::class, 'index'])->name('index');
    });

    Route::prefix('payment-gateways')->as('paymentGateways.')->group(function () {
        Route::get('/', [PaymentGatewayController::class, 'index'])->name('index');
        Route::get('/create', [PaymentGatewayController::class, 'create'])->name('create');
        Route::post('/store', [PaymentGatewayController::class, 'store'])->name('store');
        Route::get('/{gateway}/edit', [PaymentGatewayController::class, 'edit'])->name('edit');
        Route::post('/{gateway}/update', [PaymentGatewayController::class, 'update'])->name('update');
        Route::post('/{gateway}/delete', [PaymentGatewayController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('settings')->as('settings.')->group(function () {
        Route::prefix('payment-options')->as('paymentOptions.')->group(function () {
            Route::get('/', [PaymentOptionController::class, 'index'])->name('index');
            Route::post('/store', [PaymentOptionController::class, 'store'])->name('store');
            Route::post('/update/{gateway}', [PaymentOptionController::class, 'update'])->name('update');
        });
    });

    Route::prefix('manual-gateways')->as('manualGateways.')->group(function () {
        Route::get('/', [ManualPaymentMethodController::class, 'index'])->name('index');
        Route::post('/store', [ManualPaymentMethodController::class, 'store'])->name('store');
        Route::put('/{manualPayment}/update', [ManualPaymentMethodController::class, 'update'])->name('update');
        Route::delete('/{manualPayment}/delete', [ManualPaymentMethodController::class, 'delete'])->name('delete');
    });
});

Route::middleware(['web', 'seller'])->prefix('seller')->as('seller.')->group(function () {
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

Route::middleware('web')->group(function () {
    Route::get('/bkash/pay', [BkashController::class, 'pay'])->name('bkash.pay');
    Route::get('/bkash/callback', [BkashController::class, 'callback'])->name('bkash.callback');

    Route::prefix('payment')->as('payment.')->group(function () {
        // Gateways (Aamarpay) POST back to these callback URLs.
        Route::match(['get', 'post'], '/success', [PaymentController::class, 'success'])->name('success');
        Route::match(['get', 'post'], '/cancelled', [PaymentController::class, 'cancelled'])->name('cancelled');
        Route::match(['get', 'post'], '/failed', [PaymentController::class, 'failed'])->name('failed');
        Route::post('/ipn', [PaymentController::class, 'ipn'])->name('ipn');
    });
});

Route::middleware('api')->prefix('api')->group(function () {
    Route::prefix('data')->group(function () {
        Route::get('/payment-gateways', [DataController::class, 'paymentGateways']);
    });

    Route::prefix('payment-listener')->group(function () {
        Route::post('/connect', [PaymentListnerController::class, 'connectDevice']);
        Route::post('/check-device', [PaymentListnerController::class, 'checkDevice']);
        Route::post('/disconnect', [PaymentListnerController::class, 'disconnectDevice']);
        Route::post('/trigger', [PaymentListnerController::class, 'triggerSms']);
    });

    Route::prefix('mobipay')->group(function () {
        Route::match(['get', 'post'], '/webhook', [MobipayController::class, 'webhook']);
        Route::match(['get', 'post'], '/store', [MobipayController::class, 'storeSms']);
    });
});
