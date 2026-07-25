<?php

namespace App\Domain\Order\Providers;

use App\Domain\Order\Repositories\Contracts\CartRepositoryInterface;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Order\Repositories\EloquentCartRepository;
use App\Domain\Order\Repositories\EloquentOrderRepository;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);
        $this->app->bind(CartRepositoryInterface::class, EloquentCartRepository::class);
    }
}
