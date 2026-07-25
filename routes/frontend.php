<?php

use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Frontend\NotificationController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\StaticPageController;
use Illuminate\Support\Facades\Route;

Route::get('pages/{slug}', [StaticPageController::class, 'show'])->name('pages.show');

Route::get('/contact-us', [ContactUsController::class, 'contactUs'])->name('contactUs');

Route::middleware('auth')->group(function () {

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index')->middleware('markReadAuto');
});

Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

Route::get('/message', function () {
    if (! session()->has('message_data')) {
        return redirect()->route('home');
    }

    return view('frontend.pages.message', session('message_data'));
})->name('frontend.message');
