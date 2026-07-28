<?php

use App\Domain\Bundle\Http\Controllers\Frontend\BundleController as FrontendBundleController;
use App\Domain\Bundle\Http\Controllers\Seller\BundleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'seller'])->prefix('seller')->as('seller.')->group(function () {
    Route::prefix('bundles')->as('bundles.')->group(function () {
        Route::get('/', [BundleController::class, 'index'])->name('index');
        Route::get('/create', [BundleController::class, 'create'])->name('create');
        Route::post('/store', [BundleController::class, 'store'])->name('store');
        Route::get('/{bundle}', [BundleController::class, 'show'])->name('show');
        Route::get('/{bundle}/edit', [BundleController::class, 'edit'])->name('edit');
        Route::post('/{bundle}/update', [BundleController::class, 'update'])->name('update');
        Route::post('/{bundle}/duplicate', [BundleController::class, 'duplicate'])->name('duplicate');
        Route::post('/{bundle}/toggle-visibility', [BundleController::class, 'toggleVisibility'])->name('toggleVisibility');
        Route::post('/{bundle}/update-status', [BundleController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{bundle}', [BundleController::class, 'destroy'])->name('destroy');
        Route::get('/api/products', [BundleController::class, 'getProducts'])->name('api.products');
    });
});

Route::middleware('web')->prefix('bundles')->as('bundles.')->group(function () {
    Route::get('/', [FrontendBundleController::class, 'index'])->name('index');
    Route::get('/{slug}', [FrontendBundleController::class, 'show'])->name('show');
});
