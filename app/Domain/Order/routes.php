<?php

use App\Domain\Order\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Domain\Order\Http\Controllers\Api\BillingAddressController as ApiBillingAddressController;
use App\Domain\Order\Http\Controllers\Api\CartController as ApiCartController;
use App\Domain\Order\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Domain\Order\Http\Controllers\Frontend\BillingAddressController as FrontendBillingAddressController;
use App\Domain\Order\Http\Controllers\Frontend\CartController as FrontendCartController;
use App\Domain\Order\Http\Controllers\Frontend\OrderController as FrontendOrderController;
use App\Domain\Order\Http\Controllers\Frontend\ReturnController;
use App\Domain\Order\Http\Controllers\Frontend\WishlistController;
use App\Domain\Order\Http\Controllers\InvoiceController;
use App\Domain\Order\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Domain\Order\Http\Controllers\Seller\PosController;
use App\Domain\Order\Http\Controllers\Seller\SaleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        // Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    });
});

Route::middleware(['web', 'seller'])->prefix('seller')->as('seller.')->group(function () {
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

    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/', [SellerOrderController::class, 'index'])->name('index');
        Route::get('/pending', [SellerOrderController::class, 'index'])->name('pending');
        Route::get('/shipped', [SellerOrderController::class, 'index'])->name('shipped');
        Route::get('/delivered', [SellerOrderController::class, 'index'])->name('delivered');
        Route::get('/cancelled', [SellerOrderController::class, 'index'])->name('cancelled');
        Route::get('/refunded', [SellerOrderController::class, 'index'])->name('refunded');
        Route::get('/returned', [SellerOrderController::class, 'index'])->name('returned');
        Route::get('/pos-orders', [SellerOrderController::class, 'pos_orders'])->name('pos_orders');
        Route::get('/{invoice_id}/details', [SellerOrderController::class, 'details'])->name('details');
        Route::post('/{order}/update-status', [SellerOrderController::class, 'updateStatus'])->name('updateStatus');
    });
});

Route::middleware('web')->group(function () {
    Route::get('/no-order', function () {
        return view('frontend.pages.no-order');
    })->name('no_order');

    Route::get('/tracking', function () {
        return view('frontend.orders.status_logs');
    })->name('tracking');

    Route::get('/get-districts/{divisionId}', [FrontendOrderController::class, 'getDistricts']);

    Route::post('cart/add', [FrontendCartController::class, 'add'])->name('cart.add');

    Route::middleware('auth')->group(function () {
        Route::prefix('cart')->as('cart.')->group(function () {
            // Route::post('/add', [CartController::class, 'add'])->name('add');
            Route::post('/update', [FrontendCartController::class, 'update'])->name('update');
            Route::post('/delete', [FrontendCartController::class, 'delete'])->name('delete');
            Route::get('/details', [FrontendCartController::class, 'details'])->name('details');
            Route::get('/data', [FrontendCartController::class, 'getLiveCartData'])->name('data');
        });

        Route::prefix('wishlist')->as('wishlist.')->group(function () {
            Route::get('/', [WishlistController::class, 'index'])->name('index');
            Route::post('/store', [WishlistController::class, 'store'])->name('store');
            Route::delete('{wishlist}/delete', [WishlistController::class, 'delete'])->name('delete');
            Route::get('/data', [WishlistController::class, 'getLiveWishlistData'])->name('data');
        });

        Route::prefix('orders')->as('orders.')->group(function () {
            Route::get('/', [FrontendOrderController::class, 'index'])->name('index');
            Route::match(['get', 'post'], 'checkout', [FrontendOrderController::class, 'checkout'])->name('checkout');
            Route::post('{order}/pay-now', [FrontendOrderController::class, 'payNow'])->name('payNow');
            Route::get('/data', [FrontendOrderController::class, 'orderData'])->name('data');
            Route::get('/details/{invoice_id}', [FrontendOrderController::class, 'details'])->name('details');
            Route::get('/success/{invoice_id}', [FrontendOrderController::class, 'success'])->name('success');
            Route::get('/tracking/{invoice_id}', [FrontendOrderController::class, 'tracking'])->name('tracking');
            Route::match(['get', 'post'], '/review', [FrontendOrderController::class, 'review'])->name('review');
        });

        Route::prefix('returns')->as('returns.')->group(function () {
            Route::get('/', [ReturnController::class, 'index'])->name('index');
            Route::post('/store', [ReturnController::class, 'store'])->name('store');
        });

        Route::prefix('billing-addresses')->as('billing_addresses.')->group(function () {
            Route::post('/store', [FrontendBillingAddressController::class, 'store'])->name('store');
            Route::post('/{address}/update', [FrontendBillingAddressController::class, 'update'])->name('update');
            Route::post('/{address}/delete', [FrontendBillingAddressController::class, 'destroy'])->name('delete');
        });
    });

    Route::get('/invoice/{invoice_id}', [InvoiceController::class, 'invoice'])->name('invoice');
    Route::get('/receipt/{invoice_id}', [InvoiceController::class, 'receipt'])->name('receipt');
});

Route::middleware('api')->prefix('api')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::prefix('cart')->group(function () {
            Route::get('/', [ApiCartController::class, 'index']);
            Route::get('/suggestions', [ApiCartController::class, 'suggestions']);
            Route::post('/store', [ApiCartController::class, 'store']);
            Route::post('/items/{item}/delete', [ApiCartController::class, 'deleteItem']);
            Route::post('/items/{item}/update-quantity', [ApiCartController::class, 'updateQuantity']);
        });

        Route::prefix('orders')->group(function () {
            Route::get('/', [ApiOrderController::class, 'index']);
            Route::post('/store', [ApiOrderController::class, 'store']);
            Route::get('{order}', [ApiOrderController::class, 'show']);
            Route::get('{order}/invoice', [ApiOrderController::class, 'invoice']);
            Route::post('{order}/pay-now', [ApiOrderController::class, 'payNow']);
            // Route::get('{invoice_id}/tracking',[OrderController::class,'tracking']);
        });

        Route::prefix('reviews')->group(function () {
            Route::post('store', [ApiOrderController::class, 'submitReview']);
        });

        Route::prefix('billing-addresses')->group(function () {
            Route::get('/', [ApiBillingAddressController::class, 'index']);
            Route::post('/store', [ApiBillingAddressController::class, 'store']);
            Route::post('/{address}/update', [ApiBillingAddressController::class, 'update']);
        });
    });
});
