<?php

use App\Http\Controllers\Api\Seller\AuthController;
use App\Http\Controllers\Api\Seller\DashboardController;
use App\Http\Controllers\Api\Seller\ProductController;
use App\Http\Controllers\Api\Seller\OrderController;
use App\Http\Controllers\Api\Seller\EmployeeController;
use App\Http\Controllers\Api\Seller\ExpenseController;
use App\Http\Controllers\Api\Seller\ChatController;
use App\Http\Controllers\Api\Seller\SettingController;
use App\Http\Controllers\Api\Seller\ReportController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::post('/seller/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/seller/register', [AuthController::class, 'register'])->middleware('throttle:10,1');

// Authenticated seller routes
Route::middleware('auth:sanctum')->prefix('seller')->group(function () {
    // Auth & Account
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/profile/password', [AuthController::class, 'updatePassword']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard']);

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/overview', [ReportController::class, 'overview']);
        Route::get('/financial', [ReportController::class, 'financial']);
        Route::get('/sales', [ReportController::class, 'sales']);
        Route::get('/customers', [ReportController::class, 'customers']);
    });

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/create', [ProductController::class, 'create']);
    Route::post('/products/store', [ProductController::class, 'store']);
    Route::get('/products/{slug}/edit', [ProductController::class, 'edit']);
    Route::post('/products/{slug}/update', [ProductController::class, 'update']);
    Route::post('/products/{slug}/update-seo', [ProductController::class, 'updateSeo']);
    Route::post('/products/{product}/stock-update', [ProductController::class, 'stockUpdate']);
    Route::post('/products/images/upload', [ProductController::class, 'uploadImage']);
    Route::delete('/products/images/{image}/delete', [ProductController::class, 'deleteImage']);
    Route::delete('/products/delete-variant/{variant}', [ProductController::class, 'deleteVariant']);
    Route::delete('/products/{product}/delete', [ProductController::class, 'destroy']);
    Route::get('/products/inventory', [ProductController::class, 'inventory']);
    Route::get('/products/print-barcode', [ProductController::class, 'printBarcode']);
    Route::get('/products/print-labels', [ProductController::class, 'printLabels']);
    Route::get('/stock/history', [ProductController::class, 'stockHistory']);
    Route::post('/stock/update', [ProductController::class, 'bulkStockUpdate']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/pending', [OrderController::class, 'pending']);
    Route::get('/orders/shipped', [OrderController::class, 'shipped']);
    Route::get('/orders/delivered', [OrderController::class, 'delivered']);
    Route::get('/orders/cancelled', [OrderController::class, 'cancelled']);
    Route::get('/orders/refunded', [OrderController::class, 'refunded']);
    Route::get('/orders/returned', [OrderController::class, 'returned']);
    Route::get('/orders/{invoice_id}/details', [OrderController::class, 'details']);
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus']);

    // Employees
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::get('/employees/create', [EmployeeController::class, 'create']);
    Route::post('/employees/store', [EmployeeController::class, 'store']);
    Route::get('/employees/sales-report', [EmployeeController::class, 'salesReport']);
    Route::get('/employees/{id}/edit', [EmployeeController::class, 'edit']);
    Route::post('/employees/{id}/update', [EmployeeController::class, 'update']);
    Route::post('/employees/{id}/toggle-active', [EmployeeController::class, 'toggleActive']);
    Route::post('/employees/{employee}/set-permissions', [EmployeeController::class, 'setPermissions']);

    // Expenses
    Route::get('/expenses', [ExpenseController::class, 'index']);
    Route::post('/expenses/store', [ExpenseController::class, 'store']);
    Route::post('/expenses/{expense}/update', [ExpenseController::class, 'update']);
    Route::post('/expenses/{expense}/destroy', [ExpenseController::class, 'destroy']);

    // Plans & Subscriptions
    Route::get('/plans', [SettingController::class, 'plans']);
    Route::post('/plans/{plan}/subscribe', [SettingController::class, 'subscribe']);

    // Settings
    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/settings/update', [SettingController::class, 'update']);
    Route::post('/banner-image/{image}', [SettingController::class, 'deleteBannerImage']);

    // Notifications
    Route::get('/notifications', [SettingController::class, 'notifications']);

    // Customers
    Route::get('/customers', [SettingController::class, 'customers']);

    // Chat
    Route::get('/chat/list', [ChatController::class, 'chatList']);
    Route::get('/chat/messages', [ChatController::class, 'messages']);
    Route::post('/chat/send', [ChatController::class, 'sendMessage']);
});
