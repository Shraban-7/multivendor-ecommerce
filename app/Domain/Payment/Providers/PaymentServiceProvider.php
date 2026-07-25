<?php

namespace App\Domain\Payment\Providers;

use App\Domain\Payment\Repositories\Contracts\PaymentRepositoryInterface;
use App\Domain\Payment\Repositories\EloquentPaymentRepository;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentRepositoryInterface::class, EloquentPaymentRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
