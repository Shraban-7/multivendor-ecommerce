<?php

use App\Domain\Affiliate\Http\Controllers\Frontend\AffiliatorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('affiliator')->as('affiliator.')->group(function () {
        Route::get('/dashboard', [AffiliatorController::class, 'dashboard'])->name('dashboard');
        Route::match(['get', 'post'], '/withdraw', [AffiliatorController::class, 'withdraw'])->name('withdraw');
    });
});
