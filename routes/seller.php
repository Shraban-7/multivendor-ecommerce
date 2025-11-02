<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Seller\PosController;
use App\Http\Controllers\Seller\AuthController;
use App\Http\Controllers\Seller\SaleController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\OptionController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\SettingController;
use App\Http\Controllers\Seller\CustomerController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\SellerChatController;
use App\Http\Controllers\Seller\NotificationController;
use App\Http\Controllers\Seller\SellerExpenseController;
use App\Http\Controllers\Seller\ProductVariantController;
use App\Http\Controllers\Seller\SellerCampaignController;
use App\Http\Controllers\Seller\SellerEmployeeController;
use App\Http\Controllers\Seller\SubscriptionPlanController;

Route::middleware('seller')->prefix('seller')->as('seller.')->group(function () {

    Route::prefix('pos')->as('pos.')->group(function () {
        Route::get('/', [PosController::class, 'index'])->name('index');
        Route::post('/cart-add', [PosController::class, 'cartAdd'])->name('cart_add');
        Route::post('/cart/update', [PosController::class, 'cartUpdate'])->name('cart_update');
        Route::post('/cart-item/remove', [PosController::class, 'removeCartItem'])->name('remove_cart_item');
        Route::post('/cart-clear', [PosController::class, 'cartClear'])->name('cart_clear');
        Route::post('/place-order', [PosController::class, 'placeOrder'])->name('place_order');
        Route::post('/save-draft', [PosController::class, 'saveDraft'])->name('save_draft');
        Route::get('/customers/search', [PosController::class, 'customerSearch'])->name('customers.search');

        Route::prefix('sales')->as('sales.')->group(function () {
            Route::get('/', [SaleController::class, 'index'])->name('index');
            Route::post('/update', [SaleController::class, 'update'])->name('update');
            Route::post('/{id}/delete', [SaleController::class, 'delete'])->name('delete');
            Route::post('/item/add', [SaleController::class, 'itemAdd'])->name('item_add');
            Route::post('/item/update', [SaleController::class, 'itemUpdate'])->name('item_update');
            Route::post('/item/remove', [SaleController::class, 'itemRemove'])->name('item_remove');
            Route::post('/{order}/pay', [SaleController::class, 'pay'])->name('pay');
        });
    });

    Route::prefix('employees')->as('employees.')->group(function () {
        Route::get('/', [SellerEmployeeController::class, 'index'])->name('index');
        Route::get('/create', [SellerEmployeeController::class, 'create'])->name('create');
        Route::post('/store', [SellerEmployeeController::class, 'store'])->name('store');
        Route::get('{id}/edit', [SellerEmployeeController::class, 'edit'])->name('edit');
        Route::get('/profile', [SellerEmployeeController::class, 'profile'])->name('profile');
        Route::post('{id}/update', [SellerEmployeeController::class, 'update'])->name('update');
        Route::post('/update-profile', [SellerEmployeeController::class, 'updateProfile'])->name('updateProfile');
        Route::post('{id}/toggle-active', [SellerEmployeeController::class, 'toggleActive'])->name('toggle_active');
        Route::post('{employee}/set-permissions', [SellerEmployeeController::class, 'setPermissions'])->name('set_permissions');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::match(['get', 'post'], '/profile', [SellerController::class, 'profile'])->name('profile');
    Route::get('/profile-info/{username}', [SellerController::class, 'profileInfo'])->name('profileInfo');
    Route::get('/profile-info/update', [SellerController::class, 'profileUpdate'])->name('profile.update');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');

    Route::prefix('chat')->as('chat.')->group(function () {
        Route::get('/list', [SellerChatController::class, 'chatList'])->name('list');
        Route::get('/messages', [SellerChatController::class, 'messages'])->name('messages');
        Route::post('/send', [SellerChatController::class, 'sendMessage'])->name('send');
    });

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers');

    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/stock-histories', [ProductController::class, 'stockHistory'])->name('stockHistory');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/store', [ProductController::class, 'store'])->name('store');

        Route::get('/{slug}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::post('/{slug}/update', [ProductController::class, 'update'])->name('update');
        Route::post('/{slug}/update-seo', [ProductController::class, 'updateSeo'])->name('updateSeo');
        Route::post('/{product}/stock-update', [ProductController::class, 'stockUpdate'])->name('stockUpdate');
        Route::delete('/delete-variant/{variant}', [ProductController::class, 'deleteVariant'])->name('deleteVariant');
        Route::delete('images/{image}/delete', [ProductController::class, 'deleteImage'])->name('image.delete');
        Route::delete('/{product}/delete', [ProductController::class, 'delete'])->name('delete');
        Route::get('/get-options/{attributeId}', [ProductController::class, 'getOptions']);

        Route::get('print-barcode', [ProductController::class, 'printBarcode'])->name('printBarcode');
        Route::get('print-labels', [ProductController::class, 'printBarcodeLabels'])->name('printBarcodeLabels');

        Route::get('inventory', [ProductController::class, 'inventory'])->name('inventory');

        Route::get('/{product:slug}', [ProductController::class, 'show'])->name('show');
    });

    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/pending', [OrderController::class, 'index'])->name('pending');
        Route::get('/shipped', [OrderController::class, 'index'])->name('shipped');
        Route::get('/delivered', [OrderController::class, 'index'])->name('delivered');
        Route::get('/cancelled', [OrderController::class, 'index'])->name('cancelled');
        Route::get('/refunded', [OrderController::class, 'index'])->name('refunded');
        Route::get('/returned', [OrderController::class, 'index'])->name('returned');
        Route::get('/pos-orders', [OrderController::class, 'pos_orders'])->name('pos_orders');
        Route::get('/{invoice_id}/details', [OrderController::class, 'details'])->name('details');
        Route::post('/{order}/update-status', [OrderController::class, 'updateStatus'])->name('updateStatus');
    });

    Route::prefix('options')->as('options.')->group(function () {
        Route::post('{product}/store', [OptionController::class, 'store'])->name('store');
    });

    Route::prefix('product-variants')->as('productVariants.')->group(function () {
        Route::post('{product}/store', [ProductVariantController::class, 'store'])->name('store');
        Route::post('{product}/{variant}/update/', [ProductVariantController::class, 'update'])->name('update');
        Route::post('{variant}/delete', [ProductVariantController::class, 'destroy'])->name('delete');
    });

    Route::prefix('campaigns')->as('campaigns.')->group(function () {
        Route::get('/', [SellerCampaignController::class, 'index'])->name('index');
        Route::get('/create', [SellerCampaignController::class, 'create'])->name('create');
        Route::post('/store', [SellerCampaignController::class, 'store'])->name('store');
        Route::get('{campaign}/edit', [SellerCampaignController::class, 'edit'])->name('edit');
        Route::get('{campaign}/show', [SellerCampaignController::class, 'show'])->name('show');
        Route::post('{campaign}/add-products', [SellerCampaignController::class, 'add_products'])->name('add_products');
        Route::post('{campaign}/update', [SellerCampaignController::class, 'update'])->name('update');
        Route::post('{campaign}/delete', [SellerCampaignController::class, 'delete'])->name('delete');
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

    Route::prefix('subscriptions')->as('subscriptions.')->group(function () {
        Route::get('/subscriptions', [SubscriptionPlanController::class, 'index'])->name('index');
        Route::post('/subscriptions/subscribe', [SubscriptionPlanController::class, 'subscribe'])->name('subscribe');
    });

    Route::post('banner-image/{image}/', [SettingController::class, 'deleteImage'])->name('bannerImages.delete');
});
