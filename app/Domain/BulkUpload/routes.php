<?php

use App\Domain\BulkUpload\Http\Controllers\Seller\BulkUploadController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'seller'])->prefix('seller')->as('seller.')->group(function () {
    Route::prefix('bulk-upload')->as('bulk-upload.')->group(function () {
        Route::get('/', [BulkUploadController::class, 'index'])->name('index');
        Route::post('/upload', [BulkUploadController::class, 'upload'])->name('upload');
        Route::get('/preview/{bulkUpload}', [BulkUploadController::class, 'preview'])->name('preview');
        Route::post('/confirm/{bulkUpload}', [BulkUploadController::class, 'confirm'])->name('confirm');
        Route::get('/{bulkUpload}', [BulkUploadController::class, 'show'])->name('show');
        Route::get('/{bulkUpload}/download-errors', [BulkUploadController::class, 'downloadErrors'])->name('downloadErrors');
    });
});
