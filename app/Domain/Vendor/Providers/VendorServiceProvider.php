<?php

namespace App\Domain\Vendor\Providers;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\ReturnRequest;
use App\Domain\Order\Models\ReturnRequestItem;
use App\Domain\Review\Models\Review;
use App\Domain\Vendor\Models\SellerChat;
use App\Domain\Vendor\Models\SellerChatMessage;
use App\Domain\Vendor\Observers\SellerPerformanceObserver;
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
        $this->registerObservers();
    }

    protected function registerObservers(): void
    {
        $observer = $this->app->make(SellerPerformanceObserver::class);

        Review::observe($observer);
        ReturnRequest::observe($observer);
        ReturnRequestItem::observe($observer);
        Order::observe($observer);
        SellerChat::observe($observer);
        SellerChatMessage::observe($observer);
    }
}
