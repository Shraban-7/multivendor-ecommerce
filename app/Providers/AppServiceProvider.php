<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
        View::composer('*', function ($view) {
            $cart = session()->get('cart', []);
            $cartCount = count($cart);
            $totalPrice = 0;
            foreach ($cart as $item) {
                $totalPrice += $item['quantity'] * $item['selling_price'];
            }

            $view->with('cartCount', $cartCount)->with('totalPrice', $totalPrice);
        });
    }
}
