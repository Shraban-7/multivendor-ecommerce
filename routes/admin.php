<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;

Route::middleware('admin')->prefix('admin')->as('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
});

Route::middleware('guest')->prefix('admin')->as('admin.')->group(function () {
    Route::match(['get', 'post'], '/signup', [AuthController::class, 'signup'])->name('signup');
    Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
});
