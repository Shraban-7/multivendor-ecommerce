<?php

use App\Domain\Shipping\Http\Controllers\Api\LocationController as ApiLocationController;
use App\Domain\Shipping\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/get-districts/{divisionId}', [LocationController::class, 'getDistricts'])->name('get.districts');
});

Route::middleware('api')->prefix('api')->group(function () {
    Route::prefix('data')->group(function () {
        Route::get('/divisions', [ApiLocationController::class, 'divisions']);
        Route::get('/districts', [ApiLocationController::class, 'districts']);
    });
});
