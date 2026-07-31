<?php

use App\Domain\Vendor\Http\Controllers\Admin\AdminPayoutController;
use App\Domain\Vendor\Http\Controllers\Admin\SellerController as AdminSellerController;
use App\Domain\Vendor\Http\Controllers\Admin\SellerPerformanceController as AdminSellerPerformanceController;
use App\Domain\Vendor\Http\Controllers\Admin\SellerSubscriptionController;
use App\Domain\Vendor\Http\Controllers\Admin\SubscriptionPlanController as AdminSubscriptionPlanController;
use App\Domain\Vendor\Http\Controllers\Api\SellerChatController as ApiSellerChatController;
use App\Domain\Vendor\Http\Controllers\Api\SellerController as ApiSellerController;
use App\Domain\Vendor\Http\Controllers\Frontend\SellerController as FrontendSellerController;
use App\Domain\Vendor\Http\Controllers\Seller\DashboardController;
use App\Domain\Vendor\Http\Controllers\Seller\PerformanceController as SellerPerformanceController;
use App\Domain\Vendor\Http\Controllers\Seller\ReportController;
use App\Domain\Vendor\Http\Controllers\Seller\SellerChatController;
use App\Domain\Vendor\Http\Controllers\Seller\SellerController;
use App\Domain\Vendor\Http\Controllers\Seller\SellerEmployeeController;
use App\Domain\Vendor\Http\Controllers\Seller\SellerExpenseController;
use App\Domain\Vendor\Http\Controllers\Seller\SellerPayoutController;
use App\Domain\Vendor\Http\Controllers\Seller\SellerReviewController;
use App\Domain\Vendor\Http\Controllers\Seller\SettingController;
use App\Domain\Vendor\Http\Controllers\Seller\SubscriptionPlanController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::prefix('sellers')->as('sellers.')->group(function () {
        Route::get('/', [AdminSellerController::class, 'index'])->name('index');
        Route::get('/pending', [AdminSellerController::class, 'pending'])->name('pending');
        Route::get('/create', [AdminSellerController::class, 'create'])->name('create');
        Route::post('/store', [AdminSellerController::class, 'store'])->name('store');
        Route::get('{seller:username}/edit', [AdminSellerController::class, 'edit'])->name('edit');
        Route::post('{seller:username}/update', [AdminSellerController::class, 'update'])->name('update');
        Route::post('{seller}/update-status', [AdminSellerController::class, 'updateStatus'])->name('updateStatus');
        Route::post('{seller}/best-seller', [AdminSellerController::class, 'best_seller'])->name('best_seller');
        Route::post('{seller}/toggle-block', [AdminSellerController::class, 'toggleBlock'])->name('toggleBlock');
        Route::post('{seller}/delete', [AdminSellerController::class, 'delete'])->name('delete');
        Route::post('{seller}/restore', [AdminSellerController::class, 'restore'])->name('restore');
        Route::get('{seller:username}/profile', [AdminSellerController::class, 'profile'])->name('profile');
        Route::post('{seller}/permanent-delete', [AdminSellerController::class, 'permanentDelete'])->name('permanent-delete');
    });

    Route::prefix('subscription-plans')->as('subscription-plans.')->group(function () {
        Route::get('/', [AdminSubscriptionPlanController::class, 'index'])->name('index');
        Route::post('/', [AdminSubscriptionPlanController::class, 'store'])->name('store');
        Route::put('/{plan}', [AdminSubscriptionPlanController::class, 'update'])->name('update');
        Route::delete('/{plan}', [AdminSubscriptionPlanController::class, 'delete'])->name('delete');
    });

    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [SellerSubscriptionController::class, 'index'])->name('index');
    });

    Route::prefix('payouts')->as('payouts.')->group(function () {
        Route::get('/', [AdminPayoutController::class, 'index'])->name('index');
        Route::get('{payout}', [AdminPayoutController::class, 'show'])->name('show');
        Route::post('{payout}/approve', [AdminPayoutController::class, 'approve'])->name('approve');
        Route::post('{payout}/complete', [AdminPayoutController::class, 'complete'])->name('complete');
        Route::post('{payout}/cancel', [AdminPayoutController::class, 'cancel'])->name('cancel');
        Route::post('{payout}/fail', [AdminPayoutController::class, 'markFailed'])->name('fail');
    });

    Route::prefix('seller-performance')->as('seller-performance.')->group(function () {
        Route::get('/', [AdminSellerPerformanceController::class, 'index'])->name('index');
        Route::get('{seller}', [AdminSellerPerformanceController::class, 'show'])->name('show');
        Route::post('{seller}/recompute', [AdminSellerPerformanceController::class, 'recompute'])->name('recompute');
    });
});

Route::middleware(['web', 'seller'])->prefix('seller')->as('seller.')->group(function () {
    Route::prefix('employees')->as('employees.')->group(function () {
        Route::get('/', [SellerEmployeeController::class, 'index'])->name('index');
        Route::get('/create', [SellerEmployeeController::class, 'create'])->name('create');
        Route::post('/store', [SellerEmployeeController::class, 'store'])->name('store');
        Route::get('/sales-report', [SellerEmployeeController::class, 'salesReport'])->name('salesReport');
        Route::get('{id}/edit', [SellerEmployeeController::class, 'edit'])->name('edit');
        Route::get('/profile', [SellerEmployeeController::class, 'profile'])->name('profile');
        Route::post('{id}/update', [SellerEmployeeController::class, 'update'])->name('update');
        Route::post('/update-profile', [SellerEmployeeController::class, 'updateProfile'])->name('updateProfile');
        Route::post('{id}/toggle-active', [SellerEmployeeController::class, 'toggleActive'])->name('toggle_active');
        Route::post('{employee}/set-permissions', [SellerEmployeeController::class, 'setPermissions'])->name('set_permissions');
        Route::delete('{id}', [SellerEmployeeController::class, 'destroy'])->name('destroy');
    });

    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::match(['get', 'post'], '/profile', [SellerController::class, 'profile'])->name('profile');
    Route::get('/profile-info/{username}', [SellerController::class, 'profileInfo'])->name('profileInfo');
    Route::get('/profile-info/update', [SellerController::class, 'profileUpdate'])->name('profile.update');

    Route::prefix('chat')->as('chat.')->group(function () {
        Route::get('/list', [SellerChatController::class, 'chatList'])->name('list');
        Route::get('/messages', [SellerChatController::class, 'messages'])->name('messages');
        Route::post('/send', [SellerChatController::class, 'sendMessage'])->name('send');
    });

    Route::prefix('settings')->as('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/update', [SettingController::class, 'update'])->name('update');
    });

    Route::prefix('expenses')->as('expenses.')->group(function () {
        Route::get('/', [SellerExpenseController::class, 'index'])->name('index');
        Route::post('/store', [SellerExpenseController::class, 'store'])->name('store');
        Route::post('{expense}/update', [SellerExpenseController::class, 'update'])->name('update');
        Route::post('{expense}/destroy', [SellerExpenseController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('plans')->as('plans.')->group(function () {
        Route::get('/', [SubscriptionPlanController::class, 'index'])->name('index');
        Route::post('/{plan}/subscribe', [SubscriptionPlanController::class, 'subscribe'])->name('subscribe');
    });

    Route::prefix('payouts')->as('payouts.')->group(function () {
        Route::get('/', [SellerPayoutController::class, 'index'])->name('index');
        Route::get('/create', [SellerPayoutController::class, 'create'])->name('create');
        Route::post('/store', [SellerPayoutController::class, 'store'])->name('store');

        Route::prefix('methods')->as('methods.')->group(function () {
            Route::get('/', [SellerPayoutController::class, 'methods'])->name('index');
            Route::post('/store', [SellerPayoutController::class, 'storeMethod'])->name('store');
            Route::post('{method}/update', [SellerPayoutController::class, 'updateMethod'])->name('update');
            Route::delete('{method}/destroy', [SellerPayoutController::class, 'destroyMethod'])->name('destroy');
            Route::post('{method}/default', [SellerPayoutController::class, 'setDefaultMethod'])->name('default');
        });

        Route::get('{payout}', [SellerPayoutController::class, 'show'])->name('show');
    });

    Route::post('banner-image/{image}/', [SettingController::class, 'deleteImage'])->name('bannerImages.delete');

    Route::prefix('reviews')->as('reviews.')->group(function () {
        Route::get('/', [SellerReviewController::class, 'index'])->name('index');
        Route::get('{review}', [SellerReviewController::class, 'show'])->name('show');
        Route::post('{review}/reply', [SellerReviewController::class, 'reply'])->name('reply');
        Route::post('{review}/delete-reply', [SellerReviewController::class, 'deleteReply'])->name('deleteReply');
        Route::post('{review}/toggle-approval', [SellerReviewController::class, 'toggleApproval'])->name('toggleApproval');
    });

    Route::prefix('reports')->as('reports.')->group(function () {
        Route::get('/financial', [ReportController::class, 'financial'])->name('financial');
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/customers', [ReportController::class, 'customers'])->name('customers');
        Route::get('/overview', [ReportController::class, 'overview'])->name('overview');
    });

    Route::prefix('performance')->as('performance.')->group(function () {
        Route::get('/', [SellerPerformanceController::class, 'dashboard'])->name('dashboard');
        Route::get('/history', [SellerPerformanceController::class, 'history'])->name('history');
        Route::post('/recompute', [SellerPerformanceController::class, 'recompute'])->name('recompute');
    });
});

Route::middleware('api')->prefix('api')->group(function () {
    Route::get('sellers', [ApiSellerController::class, 'index']);
    Route::get('sellers/{seller}', [ApiSellerController::class, 'show']);
});

Route::middleware(['api', 'auth:sanctum'])->prefix('api')->group(function () {
    Route::prefix('chat')->group(function () {
        Route::get('/messages', [ApiSellerChatController::class, 'messages']);
        Route::post('/send', [ApiSellerChatController::class, 'sendMessage']);
    });
});

Route::middleware('web')->group(function () {
    Route::prefix('sellers')->as('sellers.')->group(function () {
        Route::get('/', [FrontendSellerController::class, 'index'])->name('index');
        Route::get('{seller:username}', [FrontendSellerController::class, 'shop'])->name('shop');
        Route::post('{seller:username}/follow', [FrontendSellerController::class, 'follow'])->middleware('auth')->name('follow');
        Route::get('{seller:username}/reviews', [FrontendSellerController::class, 'review'])->name('reviews');
        Route::post('/reviews/{review}/helpful', [FrontendSellerController::class, 'markHelpful'])->name('reviews.helpful');
        Route::post('/reviews/report', [FrontendSellerController::class, 'reviewReport'])->name('reviews.report');
    });
});
