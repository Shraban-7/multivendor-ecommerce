<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\StaticPageController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin')->prefix('admin')->as('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::prefix('customers')->as('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::post('/update', [CustomerController::class, 'update'])->name('update');
        Route::get('{customer:username}/profile', [CustomerController::class, 'profile'])->name('profile');
    });

    Route::prefix('roles')->as('roles.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::post('/store', [PermissionController::class, 'store'])->name('store');
        Route::get('{role}/edit', [PermissionController::class, 'edit'])->name('edit');
        Route::post('{role}/update', [PermissionController::class, 'update'])->name('update');
    });

    Route::match(['get', 'post'], '/profile', [ProfileController::class, 'profile'])->name('profile');

    Route::prefix('admins')->as('admins.')->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/add', [AdminController::class, 'add'])->name('create');
        Route::post('/store', [AdminController::class, 'store'])->name('store');
        Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::post('/{admin}/update', [AdminController::class, 'update'])->name('update');
        Route::delete('/{admin}/delete', [AdminController::class, 'delete'])->name('delete');
    });

    Route::prefix('settings')->as('settings.')->group(function () {
        Route::prefix('social-links')->as('socialLinks.')->group(function () {
            Route::get('/', [SocialLinkController::class, 'index'])->name('index');
            Route::post('/store', [SocialLinkController::class, 'store'])->name('store');
            Route::post('/update/{socialLink}', [SocialLinkController::class, 'update'])->name('update');
        });

        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/update', [SettingController::class, 'update'])->name('update');
    });

    Route::prefix('images')->as('images.')->group(function () {
        Route::get('/', [ImageController::class, 'index'])->name('index');
        Route::post('/store', [ImageController::class, 'store'])->name('store');
        Route::delete('/delete-all', [ImageController::class, 'deleteAll'])->name('delete-all');
        Route::post('/cropped-image', [ImageController::class, 'croppedImage'])->name('cropped-image');
        Route::delete('/delete-cropped-image', [ImageController::class, 'deleteCroppedImage'])->name('delete-cropped-image');
    });

    Route::prefix('static-pages')->name('staticPages.')->controller(StaticPageController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{slug}/edit', 'edit')->name('edit');
        Route::put('/{slug}/update', 'update')->name('update');
    });

});

Route::middleware('guest:admin')->prefix('admin')->as('admin.')->group(function () {
    Route::match(['get', 'post'], '/signup', [AuthController::class, 'signup'])->name('signup');
    Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
});
