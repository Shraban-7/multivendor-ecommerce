<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\HomeMidController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\ManualPaymentMethodController;
use App\Http\Controllers\Admin\OptionController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentGatewayController;
use App\Http\Controllers\Admin\PaymentOptionController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ReviewsController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\SellerSubscriptionController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SocialLinkController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use Illuminate\Support\Facades\Route;

Route::middleware('admin')->prefix('admin')->as('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::prefix('customers')->as('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::post('/update', [CustomerController::class, 'update'])->name('update');
        Route::get('{customer:username}/profile', [CustomerController::class, 'profile'])->name('profile');
    });

    Route::prefix('sellers')->as('sellers.')->group(function () {
        Route::get('/', [SellerController::class, 'index'])->name('index');
        Route::get('/pending', [SellerController::class, 'pending'])->name('pending');
        Route::get('/create', [SellerController::class, 'create'])->name('create');
        Route::post('/store', [SellerController::class, 'store'])->name('store');
        Route::get('{seller:username}/edit', [SellerController::class, 'edit'])->name('edit');
        Route::post('{seller:username}/update', [SellerController::class, 'update'])->name('update');
        Route::post('{seller}/update-status', [SellerController::class, 'updateStatus'])->name('updateStatus');
        Route::post('{seller}/best-seller', [SellerController::class, 'best_seller'])->name('best_seller');
        Route::post('{seller}/toggle-block', [SellerController::class, 'toggleBlock'])->name('toggleBlock');
        Route::post('{seller}/delete', [SellerController::class, 'delete'])->name('delete');
        Route::post('{seller}/restore', [SellerController::class, 'restore'])->name('restore');
        Route::get('{seller:username}/profile', [SellerController::class, 'profile'])->name('profile');
        Route::post('{seller}/permanent-delete', [SellerController::class, 'permanentDelete'])->name('permanent-delete');
    });

    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/{id}/status', [ProductController::class, 'updateStatus'])
            ->name('updateStatus');
    });

    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        // Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    });
    Route::prefix('payments')->as('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
    });

    Route::prefix('options')->as('options.')->group(function () {
        Route::get('/', [OptionController::class, 'index'])->name('index');
        Route::post('/store', [OptionController::class, 'store'])->name('store');
        //Route::post('values/{value}/delete', [OptionController::class, 'deleteValue'])->name('option_value_delete');
        Route::post('{option}/update', [OptionController::class, 'update'])->name('update');
        Route::post('{option}/delete', [OptionController::class, 'destroy'])->name('delete');
    });

    Route::prefix('brands')->as('brands.')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::post('/store', [BrandController::class, 'store'])->name('store');
        Route::post('/update/{brand}', [BrandController::class, 'update'])->name('update');
        Route::post('/toggle-status/{brand}', [BrandController::class, 'toggleStatus'])->name('toggleStatus');
    });

    Route::prefix('categories')->as('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::get('{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{category}', [CategoryController::class, 'update'])->name('update');
        Route::post('/toggle-status/{category}', [CategoryController::class, 'toggleStatus'])->name('toggleStatus');
    });

    Route::prefix('subcategories')->as('subcategories.')->group(function () {
        Route::get('/', [SubcategoryController::class, 'index'])->name('index');
        Route::get('/create', [SubcategoryController::class, 'create'])->name('create');
        Route::post('/store', [SubcategoryController::class, 'store'])->name('store');
        Route::get('{subcategory}/edit', [SubcategoryController::class, 'edit'])->name('edit');
        Route::post('/update/{subcategory}', [SubcategoryController::class, 'update'])->name('update');
        Route::post('/toggle-status/{subcategory}', [SubcategoryController::class, 'toggleStatus'])->name('toggleStatus');
    });

    Route::prefix('reviews')->as('reviews.')->group(function () {
        Route::get('/', [ReviewsController::class, 'index'])->name('index');
        Route::post('/{review}/delete', [ReviewsController::class, 'destroy'])->name('destroy');
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
        Route::post('/{admin}/delete', [AdminController::class, 'delete'])->name('delete');
    });

    Route::prefix('payment-gateways')->as('paymentGateways.')->group(function () {
        Route::get('/', [PaymentGatewayController::class, 'index'])->name('index');
        Route::get('/create', [PaymentGatewayController::class, 'create'])->name('create');
        Route::post('/store', [PaymentGatewayController::class, 'store'])->name('store');
        Route::get('/{gateway}/edit', [PaymentGatewayController::class, 'edit'])->name('edit');
        Route::post('/{gateway}/update', [PaymentGatewayController::class, 'update'])->name('update');
        Route::post('/{gateway}/delete', [PaymentGatewayController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('settings')->as('settings.')->group(function () {
        Route::prefix('social-links')->as('socialLinks.')->group(function () {
            Route::get('/', [SocialLinkController::class, 'index'])->name('index');
            Route::post('/store', [SocialLinkController::class, 'store'])->name('store');
            Route::post('/update/{socialLink}', [SocialLinkController::class, 'update'])->name('update');
        });

        Route::prefix('payment-options')->as('paymentOptions.')->group(function () {
            Route::get('/', [PaymentOptionController::class, 'index'])->name('index');
            Route::post('/store', [PaymentOptionController::class, 'store'])->name('store');
            Route::post('/update/{gateway}', [PaymentOptionController::class, 'update'])->name('update');
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

    Route::prefix('manual-gateways')->as('manualGateways.')->group(function () {
        Route::get('/', [ManualPaymentMethodController::class, 'index'])->name('index');
        Route::post('/store', [ManualPaymentMethodController::class, 'store'])->name('store');
        Route::put('/{manualPayment}/update', [ManualPaymentMethodController::class, 'update'])->name('update');
        Route::delete('/{manualPayment}/delete', [ManualPaymentMethodController::class, 'delete'])->name('delete');
    });

    Route::prefix('subscription-plans')->as('subscription-plans.')->group(function () {
        Route::get('/', [SubscriptionPlanController::class, 'index'])->name('index');
        Route::post('/', [SubscriptionPlanController::class, 'store'])->name('store');
        Route::put('/{plan}', [SubscriptionPlanController::class, 'update'])->name('update');
        Route::delete('/{plan}', [SubscriptionPlanController::class, 'delete'])->name('delete');
    });

    Route::prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [SellerSubscriptionController::class, 'index'])->name('index');
        // Route::get('/{sellerSubscription}', [SellerSubscriptionController::class, 'show'])->name('show');
        // Route::get('/{sellerSubscription}/edit', [SellerSubscriptionController::class, 'edit'])->name('edit');
        // Route::put('/{sellerSubscription}', [SellerSubscriptionController::class, 'update'])->name('update');
        // Route::post('/{sellerSubscription}/cancel', [SellerSubscriptionController::class, 'cancel'])->name('cancel');
        // Route::post('/{sellerSubscription}/renew', [SellerSubscriptionController::class, 'renew'])->name('renew');
        // Route::post('/{sellerSubscription}/suspend', [SellerSubscriptionController::class, 'suspend'])->name('suspend');
        // Route::post('/{sellerSubscription}/activate', [SellerSubscriptionController::class, 'activate'])->name('activate');
    });

    // Route::get('/subscriptions', [SubscriptionPlanController::class, 'subscriptions'])->name('subscriptions.index');

    Route::prefix('static-pages')->name('staticPages.')->controller(StaticPageController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/{slug}/edit', 'edit')->name('edit');
        Route::put('/{slug}/update', 'update')->name('update');
    });

    Route::resource('banners', BannerController::class)->names('banners');

    Route::get('/flash-sales', [FlashSaleController::class, 'index'])->name('flash-sales.index');
    Route::get('/flash-sales/create', [FlashSaleController::class, 'create'])->name('flash-sales.create');
    Route::post('/flash-sales/store', [FlashSaleController::class, 'store'])->name('flash-sales.store');
    Route::get('/flash-sales/{id}/edit', [FlashSaleController::class, 'edit'])->name('flash-sales.edit');
    Route::put('/flash-sales/{id}/update', [FlashSaleController::class, 'update'])->name('flash-sales.update');
    Route::get('/flash-sales/{id}', [FlashSaleController::class, 'show'])->name('flash-sales.show');
    Route::delete('/flash-sales/{id}', [FlashSaleController::class, 'destroy'])->name('flash-sales.delete');
});

Route::middleware('guest')->prefix('admin')->as('admin.')->group(function () {
    Route::match(['get', 'post'], '/signup', [AuthController::class, 'signup'])->name('signup');
    Route::match(['get', 'post'], '/login', [AuthController::class, 'login'])->name('login');
});
