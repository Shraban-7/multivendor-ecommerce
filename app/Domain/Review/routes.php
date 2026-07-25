<?php

use App\Domain\Review\Http\Controllers\Admin\ReviewsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::prefix('reviews')->as('reviews.')->group(function () {
        Route::get('/', [ReviewsController::class, 'index'])->name('index');
        Route::post('/{review}/delete', [ReviewsController::class, 'destroy'])->name('destroy');
    });
});
