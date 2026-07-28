<?php

use App\Domain\Product\Http\Controllers\Admin\BannerController;
use App\Domain\Product\Http\Controllers\Admin\BrandController;
use App\Domain\Product\Http\Controllers\Admin\CategoryController;
use App\Domain\Product\Http\Controllers\Admin\ColorController;
use App\Domain\Product\Http\Controllers\Admin\FlashSaleController;
use App\Domain\Product\Http\Controllers\Admin\OptionController;
use App\Domain\Product\Http\Controllers\Admin\ProductController;
use App\Domain\Product\Http\Controllers\Admin\SizeController;
use App\Domain\Product\Http\Controllers\Admin\SubcategoryController;
use App\Domain\Product\Http\Controllers\Api\CategoryController as ApiCategoryController;
use App\Domain\Product\Http\Controllers\Api\ProductController as ApiProductController;
use App\Domain\Product\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Domain\Product\Http\Controllers\Frontend\FlashSaleController as FrontendFlashSaleController;
use App\Domain\Product\Http\Controllers\Frontend\ProductController as FrontendProductController;
use App\Domain\Product\Http\Controllers\Seller\FlashSaleController as SellerFlashSaleController;
use App\Domain\Product\Http\Controllers\Seller\OptionController as SellerOptionController;
use App\Domain\Product\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Domain\Product\Http\Controllers\Seller\ProductMediaController;
use App\Domain\Product\Http\Controllers\Seller\ProductStockController;
use App\Domain\Product\Http\Controllers\Seller\ProductVariantController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'admin'])->prefix('admin')->as('admin.')->group(function () {
    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/{id}/status', [ProductController::class, 'updateStatus'])
            ->name('updateStatus');
    });

    Route::prefix('options')->as('options.')->group(function () {
        Route::get('/', [OptionController::class, 'index'])->name('index');
        Route::post('/store', [OptionController::class, 'store'])->name('store');
        Route::post('values/{id}/update', [OptionController::class, 'optionValueUpdate'])->name('option_value_update');
        Route::post('{option}/update', [OptionController::class, 'update'])->name('update');
        Route::post('{option}/delete', [OptionController::class, 'destroy'])->name('delete');
    });

    Route::prefix('colors')->as('colors.')->group(function () {
        Route::get('/', [ColorController::class, 'index'])->name('index');
        Route::post('/store', [ColorController::class, 'store'])->name('store');
        Route::post('/{color}/update', [ColorController::class, 'update'])->name('update');
        Route::post('/{color}/delete', [ColorController::class, 'destroy'])->name('delete');
    });

    Route::prefix('sizes')->as('sizes.')->group(function () {
        Route::get('/', [SizeController::class, 'index'])->name('index');
        Route::post('/store', [SizeController::class, 'store'])->name('store');
        Route::post('/{size}/update', [SizeController::class, 'update'])->name('update');
        Route::post('/{size}/delete', [SizeController::class, 'destroy'])->name('delete');
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

    Route::resource('banners', BannerController::class)->names('banners');

    Route::prefix('flash-sales')->as('flash-sales.')->group(function () {
        Route::get('/', [FlashSaleController::class, 'index'])->name('index');
        Route::get('/create', [FlashSaleController::class, 'create'])->name('create');
        Route::post('/store', [FlashSaleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [FlashSaleController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [FlashSaleController::class, 'update'])->name('update');
        Route::get('/{id}', [FlashSaleController::class, 'show'])->name('show');
        Route::delete('/{id}', [FlashSaleController::class, 'destroy'])->name('delete');
        Route::post('/{id}/product/{productId}/review', [FlashSaleController::class, 'productReview'])->name('product.review');
    });
});

Route::middleware(['web', 'seller'])->prefix('seller')->as('seller.')->group(function () {
    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', [SellerProductController::class, 'index'])->name('index');

        Route::get('/create', [SellerProductController::class, 'create'])->name('create');
        Route::post('/store', [SellerProductController::class, 'store'])->name('store');

        Route::get('/{slug}/edit', [SellerProductController::class, 'edit'])->name('edit');
        Route::post('/{slug}/update', [SellerProductController::class, 'update'])->name('update');
        Route::post('/{slug}/update-seo', [SellerProductController::class, 'updateSeo'])->name('updateSeo');
        Route::post('/{product}/stock-update', [SellerProductController::class, 'stockUpdate'])->name('stockUpdate');
        Route::delete('/delete-variant/{variant}', [SellerProductController::class, 'deleteVariant'])->name('deleteVariant');
        Route::post('images/upload', [SellerProductController::class, 'uploadImages'])->name('uploadImages');
        Route::delete('images/{image}/delete', [SellerProductController::class, 'deleteImage'])->name('image.delete');

        Route::prefix('{product}/media')->as('media.')->group(function () {
            Route::get('/', [ProductMediaController::class, 'index'])->name('index');
            Route::post('/upload', [ProductMediaController::class, 'upload'])->name('upload');
            Route::delete('{image}', [ProductMediaController::class, 'destroy'])->name('destroy');
            Route::post('/reorder', [ProductMediaController::class, 'reorder'])->name('reorder');
            Route::post('{image}/primary', [ProductMediaController::class, 'setPrimary'])->name('setPrimary');
            Route::post('{image}/replace', [ProductMediaController::class, 'replace'])->name('replace');
        });

        Route::post('/{product}/duplicate', [SellerProductController::class, 'duplicate'])->name('duplicate');
        Route::post('/{product}/toggle-visibility', [SellerProductController::class, 'toggleVisibility'])->name('toggleVisibility');
        Route::delete('/{product}/delete', [SellerProductController::class, 'delete'])->name('delete');
        Route::get('/get-options/{attributeId}', [SellerProductController::class, 'getOptions']);

        Route::get('print-barcode', [SellerProductController::class, 'printBarcode'])->name('printBarcode');
        Route::get('print-labels', [SellerProductController::class, 'printBarcodeLabels'])->name('printBarcodeLabels');

        Route::get('inventory', [SellerProductController::class, 'inventory'])->name('inventory');

        Route::get('/{product:slug}', [SellerProductController::class, 'show'])->name('show');
    });

    Route::prefix('stock')->as('stock.')->group(function () {
        Route::get('/history', [ProductStockController::class, 'index'])->name('index');
        Route::get('/products', [ProductStockController::class, 'products'])->name('products');
        Route::get('/variants', [ProductStockController::class, 'variants'])->name('variants');
        Route::post('/update', [ProductStockController::class, 'update'])->name('update');
    });

    Route::prefix('options')->as('options.')->group(function () {
        Route::post('{product}/store', [SellerOptionController::class, 'store'])->name('store');
    });

    Route::prefix('product-variants')->as('productVariants.')->group(function () {
        Route::post('{product}/store', [ProductVariantController::class, 'store'])->name('store');
        Route::post('{variant}/update', [ProductVariantController::class, 'update'])->name('update');
        Route::post('{variant}/toggle-status', [ProductVariantController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('{variant}/delete', [ProductVariantController::class, 'destroy'])->name('delete');
    });

    Route::prefix('flash-sales')->as('flash-sales.')->group(function () {
        Route::get('/', [SellerFlashSaleController::class, 'index'])->name('index');
        Route::get('/{id}', [SellerFlashSaleController::class, 'details'])->name('details');
        Route::post('/{id}/submit', [SellerFlashSaleController::class, 'submit'])->name('submit');
    });
});

Route::middleware('api')->prefix('api')->group(function () {
    Route::get('categories', [ApiCategoryController::class, 'index']);
    Route::get('products', [ApiProductController::class, 'index'])->middleware('throttle:60,1');
    Route::get('products/{product}', [ApiProductController::class, 'show'])->middleware('throttle:60,1');
});

Route::middleware('web')->group(function () {
    Route::get('categories/{slug}', [FrontendCategoryController::class, 'details'])->name('category.details');

    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', [FrontendProductController::class, 'index'])->name('index');
        Route::post('{slug}/get-variant', [FrontendProductController::class, 'getVariant']);
        Route::get('{product}', [FrontendProductController::class, 'details'])->name('details');
    });

    Route::prefix('flash-sales')->as('flashSales.')->group(function () {
        Route::get('/', [FrontendFlashSaleController::class, 'index'])->name('index');
        Route::get('/{id}', [FrontendFlashSaleController::class, 'show'])->name('show');
    });
});
