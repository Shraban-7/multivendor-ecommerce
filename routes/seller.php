<?php

use App\Http\Controllers\Seller\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller')->as('seller.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');   
});
