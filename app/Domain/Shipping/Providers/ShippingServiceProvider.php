<?php

namespace App\Domain\Shipping\Providers;

use App\Domain\Shipping\Repositories\EloquentLocationRepository;
use App\Domain\Shipping\Repositories\EloquentShippingRepository;
use App\Domain\Shipping\Repositories\LocationRepositoryInterface;
use App\Domain\Shipping\Repositories\ShippingRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class ShippingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LocationRepositoryInterface::class, EloquentLocationRepository::class);
        $this->app->bind(ShippingRepositoryInterface::class, EloquentShippingRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
