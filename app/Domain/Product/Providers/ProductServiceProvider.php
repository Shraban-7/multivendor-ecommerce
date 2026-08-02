<?php

namespace App\Domain\Product\Providers;

use App\Domain\Product\Console\SyncProductCatalogCommand;
use App\Domain\Product\Models\Banner;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Observers\BannerObserver;
use App\Domain\Product\Observers\CategoryObserver;
use App\Domain\Product\Observers\ProductObserver;
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
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->commands([
            SyncProductCatalogCommand::class,
        ]);
        Product::observe(ProductObserver::class);
        Banner::observe(BannerObserver::class);
        Category::observe(CategoryObserver::class);
    }
}
