<?php

namespace App\Domain\Product\Providers;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\Contracts\BrandRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\CategoryRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\FlashSaleRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\OptionRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\ProductRepositoryInterface;
use App\Domain\Product\Repositories\EloquentBrandRepository;
use App\Domain\Product\Repositories\EloquentCategoryRepository;
use App\Domain\Product\Repositories\EloquentFlashSaleRepository;
use App\Domain\Product\Repositories\EloquentOptionRepository;
use App\Domain\Product\Repositories\EloquentProductRepository;
use App\Observers\ProductObserver;
use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, EloquentProductRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, EloquentCategoryRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, EloquentBrandRepository::class);
        $this->app->bind(FlashSaleRepositoryInterface::class, EloquentFlashSaleRepository::class);
        $this->app->bind(OptionRepositoryInterface::class, EloquentOptionRepository::class);
    }

    public function boot(): void
    {
        Product::observe(ProductObserver::class);
    }
}
