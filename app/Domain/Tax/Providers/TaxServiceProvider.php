<?php

namespace App\Domain\Tax\Providers;

use App\Domain\Tax\Services\TaxCalculatorService;
use Illuminate\Support\ServiceProvider;

class TaxServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TaxCalculatorService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
