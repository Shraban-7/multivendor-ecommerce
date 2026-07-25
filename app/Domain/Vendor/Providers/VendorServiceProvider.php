<?php

namespace App\Domain\Vendor\Providers;

use App\Domain\Vendor\Repositories\EloquentSellerEmployeeRepository;
use App\Domain\Vendor\Repositories\EloquentSellerRepository;
use App\Domain\Vendor\Repositories\SellerEmployeeRepositoryInterface;
use App\Domain\Vendor\Repositories\SellerRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class VendorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(SellerRepositoryInterface::class, EloquentSellerRepository::class);
        $this->app->bind(SellerEmployeeRepositoryInterface::class, EloquentSellerEmployeeRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
