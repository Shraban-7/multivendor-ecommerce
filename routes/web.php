<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Frontend\HomeController;

Route::get('/', [HomeController::class,'index'])->name('home');
Route::get('/test',function(){
    return view('frontend.pages.test');
})->name('test');

Route::get('/states/{country_id}', [LocationController::class, 'getStatesByCountry'])->name('getStatesByCountry');

Route::get('/invoice/{order}', [OrderController::class, 'invoice'])->name('invoice');


Route::get('/product', function () {
    return view('product-variant');
});


