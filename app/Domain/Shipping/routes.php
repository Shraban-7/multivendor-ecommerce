<?php

use App\Domain\Shipping\Http\Controllers\Admin\AdminShippingCarrierController;
use App\Domain\Shipping\Http\Controllers\Api\LocationController as ApiLocationController;
use App\Domain\Shipping\Http\Controllers\LocationController;
use App\Domain\Shipping\Http\Controllers\Seller\SellerShippingController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/get-districts/{divisionId}', [LocationController::class, 'getDistricts'])->name('get.districts');
});

Route::middleware(['web', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::prefix('shipping')->as('shipping.')->group(function () {
        Route::prefix('carriers')->as('carriers.')->group(function () {
            Route::get('/', [AdminShippingCarrierController::class, 'index'])->name('index');
            Route::post('/', [AdminShippingCarrierController::class, 'store'])->name('store');
            Route::post('{carrier}/update', [AdminShippingCarrierController::class, 'update'])->name('update');
            Route::delete('{carrier}/destroy', [AdminShippingCarrierController::class, 'destroy'])->name('destroy');
        });
    });
});

Route::middleware(['web', 'seller'])->prefix('seller')->as('seller.')->group(function () {
    Route::prefix('shipping')->as('shipping.')->group(function () {
        Route::get('/zones', [SellerShippingController::class, 'zones'])->name('zones');
        Route::post('/zones/store', [SellerShippingController::class, 'storeZone'])->name('zones.store');
        Route::post('/zones/{zone}/update', [SellerShippingController::class, 'updateZone'])->name('zones.update');
        Route::delete('/zones/{zone}/destroy', [SellerShippingController::class, 'destroyZone'])->name('zones.destroy');
    });

    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('{order}/tracking', [SellerShippingController::class, 'trackingForm'])->name('tracking');
        Route::post('{order}/tracking/store', [SellerShippingController::class, 'storeTracking'])->name('tracking.store');
    });
});

Route::middleware('api')->prefix('api')->group(function () {
    Route::prefix('data')->group(function () {
        Route::get('/divisions', [ApiLocationController::class, 'divisions']);
        Route::get('/districts', [ApiLocationController::class, 'districts']);
    });
});
