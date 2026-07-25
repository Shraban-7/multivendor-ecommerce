<?php

use App\Http\Controllers\Seller\AuthController;
use App\Http\Controllers\Seller\CustomerController;
use App\Http\Controllers\Seller\NotificationController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\PaymentListnerController;
use App\Http\Controllers\Seller\PosController;
use App\Http\Controllers\Seller\SaleController;
use Illuminate\Support\Facades\Route;

Route::middleware('seller')->prefix('seller')->as('seller.')->group(function () {

    Route::prefix('pos')->as('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/cart-add', [PosController::class, 'cartAdd'])->name('cart_add');
        Route::post('/cart/update', [PosController::class, 'cartUpdate'])->name('cart_update');
        Route::post('/cart-item/remove', [PosController::class, 'removeCartItem'])->name('remove_cart_item');
        Route::post('/cart-clear', [PosController::class, 'cartClear'])->name('cart_clear');
        Route::post('/place-order', [PosController::class, 'placeOrder'])->name('place_order');
        Route::post('/save-draft', [PosController::class, 'saveDraft'])->name('save_draft');
        Route::post('/draft-clear/{draft}', [PosController::class, 'draftClear'])->name('draft_clear');
        Route::get('/customers/search', [PosController::class, 'customerSearch'])->name('customers.search')->middleware('throttle:30,1');

        Route::prefix('sales')->as('sales.')->group(function () {
            Route::get('/', [SaleController::class, 'index'])->name('index');
            Route::post('/update', [SaleController::class, 'update'])->name('update');
            Route::post('/{id}/delete', [SaleController::class, 'delete'])->name('delete');
            Route::post('/item/add', [SaleController::class, 'itemAdd'])->name('item_add');
            Route::post('/item/update', [SaleController::class, 'itemUpdate'])->name('item_update');
            Route::post('/item/remove', [SaleController::class, 'itemRemove'])->name('item_remove');
            Route::post('/{order}/pay', [SaleController::class, 'pay'])->name('pay');
        });
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/pending', [OrderController::class, 'index'])->name('pending');
        Route::get('/shipped', [OrderController::class, 'index'])->name('shipped');
        Route::get('/delivered', [OrderController::class, 'index'])->name('delivered');
        Route::get('/cancelled', [OrderController::class, 'index'])->name('cancelled');
        Route::get('/refunded', [OrderController::class, 'index'])->name('refunded');
        Route::get('/returned', [OrderController::class, 'index'])->name('returned');
        Route::get('/pos-orders', [OrderController::class, 'pos_orders'])->name('pos_orders');
        Route::get('/{invoice_id}/details', [OrderController::class, 'details'])->name('details');
        Route::post('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('updateStatus');
    });

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
