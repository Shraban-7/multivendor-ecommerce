<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PaymentController;
use App\Models\Product;

// Route::get('images', function () {
//     $products = Product::with('variants')->select('id', 'thumbnail')->get();
//     $images = [];
//     foreach ($products as $product) {
//         if (!is_null($product->thumbnail)) {
//             $images[] = storage_url($product->thumbnail);
//         }
//         foreach ($product->variants as $variant) {
//             if (!is_null($variant->image)) {
//                 $images[] = storage_url($variant->image);
//             }
//         }
//     }

//     return $images;
// });

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/invoice/{invoice_id}', [InvoiceController::class, 'invoice'])->name('invoice');
Route::get('/receipt/{invoice_id}', [InvoiceController::class, 'receipt'])->name('receipt');

Route::get('/get-districts/{divisionId}', [LocationController::class, 'getDistricts'])->name('get.districts');

Route::prefix('payment')->as('payment.')->group(function () {
    Route::get('/pay', [PaymentController::class, 'pay'])->name('pay');
    Route::middleware('aamarpay')->group(function () {
        Route::post('/success', [PaymentController::class, 'confirm'])->name('success');
        Route::post('/cancel', [PaymentController::class, 'cancel'])->name('cancel');
        Route::post('/notify', [PaymentController::class, 'notify'])->name('notify');
    });
    Route::get('/test', function () {
        return view('payment.test');
    })->middleware('auth');
    Route::get('/mail', function () {
        return view('payment.mail');
    });
    Route::get('/manual', [PaymentController::class, 'manual']);
});
