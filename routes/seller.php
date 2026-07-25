<?php

use App\Http\Controllers\Seller\AuthController;
use App\Http\Controllers\Seller\CustomerController;
use App\Http\Controllers\Seller\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware('seller')->prefix('seller')->as('seller.')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');

});
