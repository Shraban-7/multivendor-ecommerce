<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\CustomerController;
use App\Http\Controllers\Api\Admin\RoleController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\Admin\ImageController;
use App\Http\Controllers\Api\Admin\StaticPageController;
use App\Http\Controllers\Api\Admin\SocialLinkController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::post('/admin/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/admin/signup', [AuthController::class, 'signup'])->middleware('throttle:10,1');

// Authenticated admin routes
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    // Auth & Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);

    // Customers
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::post('/customers/update', [CustomerController::class, 'update']);
    Route::get('/customers/{customer:username}/profile', [CustomerController::class, 'profile']);

    // Roles & Permissions
    Route::get('/roles', [RoleController::class, 'index']);
    Route::post('/roles/store', [RoleController::class, 'store']);
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit']);
    Route::post('/roles/{role}/update', [RoleController::class, 'update']);

    // System Admins
    Route::get('/admins', [AdminController::class, 'index']);
    Route::get('/admins/add', [AdminController::class, 'create']);
    Route::post('/admins/store', [AdminController::class, 'store']);
    Route::get('/admins/{admin}/edit', [AdminController::class, 'edit']);
    Route::post('/admins/{admin}/update', [AdminController::class, 'update']);
    Route::delete('/admins/{admin}/delete', [AdminController::class, 'destroy']);

    // Settings
    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/settings/update', [SettingController::class, 'update']);

    // Social Links
    Route::get('/settings/social-links', [SocialLinkController::class, 'index']);
    Route::post('/settings/social-links/store', [SocialLinkController::class, 'store']);
    Route::post('/settings/social-links/update/{socialLink}', [SocialLinkController::class, 'update']);

    // Media / Images
    Route::get('/images', [ImageController::class, 'index']);
    Route::post('/images/store', [ImageController::class, 'store']);
    Route::delete('/images/delete-all', [ImageController::class, 'deleteAll']);
    Route::post('/images/cropped-image', [ImageController::class, 'croppedImage']);
    Route::delete('/images/delete-cropped-image', [ImageController::class, 'deleteCroppedImage']);

    // Static Pages
    Route::get('/static-pages', [StaticPageController::class, 'index']);
    Route::post('/static-pages/store', [StaticPageController::class, 'store']);
    Route::get('/static-pages/{slug}/edit', [StaticPageController::class, 'edit']);
    Route::put('/static-pages/{slug}/update', [StaticPageController::class, 'update']);
});
