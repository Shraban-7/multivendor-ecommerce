<?php

use App\Domain\Support\Http\Controllers\Admin\SupportController as AdminSupportController;
use App\Domain\Support\Http\Controllers\Seller\SupportController as SellerSupportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::prefix('support')->as('support.')->group(function () {
        Route::get('/', [AdminSupportController::class, 'index'])->name('index');
        Route::get('{ticket}', [AdminSupportController::class, 'show'])->name('show');
        Route::post('{ticket}/reply', [AdminSupportController::class, 'reply'])->name('reply');
        Route::post('{ticket}/status', [AdminSupportController::class, 'changeStatus'])->name('status');
        Route::post('{ticket}/priority', [AdminSupportController::class, 'changePriority'])->name('priority');
        Route::post('{ticket}/assign', [AdminSupportController::class, 'assign'])->name('assign');
        Route::post('{ticket}/self-assign', [AdminSupportController::class, 'selfAssign'])->name('selfAssign');
        Route::post('{ticket}/resolve', [AdminSupportController::class, 'resolve'])->name('resolve');
        Route::post('{ticket}/reopen', [AdminSupportController::class, 'reopen'])->name('reopen');
    });
});

Route::middleware(['web', 'seller'])->prefix('seller')->as('seller.')->group(function () {
    Route::prefix('support')->as('support.')->group(function () {
        Route::get('/', [SellerSupportController::class, 'index'])->name('index');
        Route::get('/create', [SellerSupportController::class, 'create'])->name('create');
        Route::post('/store', [SellerSupportController::class, 'store'])->name('store');
        Route::get('{ticket}', [SellerSupportController::class, 'show'])->name('show');
        Route::post('{ticket}/reply', [SellerSupportController::class, 'reply'])->name('reply');
        Route::post('{ticket}/resolve', [SellerSupportController::class, 'resolve'])->name('resolve');
        Route::post('{ticket}/reopen', [SellerSupportController::class, 'reopen'])->name('reopen');
    });
});
