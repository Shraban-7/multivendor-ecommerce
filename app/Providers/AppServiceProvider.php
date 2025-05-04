<?php

namespace App\Providers;

use App\Models\Cart;
use App\Enums\DiscountType;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
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
            if (Auth::check()) {
                $carts = Cart::where('user_id', Auth::user()->id)
                    ->with('cartItems.product', 'cartItems.variant')
                    ->get();
                $cartCount = count($carts);
            } else {
                $carts = [];
                $cartCount = 0;
            }

            $sub_total = 0;
            $grand_total = 0;

            foreach ($carts as $cart) {
                foreach ($cart->cartItems as $item) {
                    $item_grand_total = $item->quantity * $item->price;
                    $grand_total += $item_grand_total;
                    $sub_total += $item_grand_total;
                }
            }

            $view->with('cartCount', $cartCount)->with('totalPrice', $grand_total)->with('subTotal', $sub_total);
        });
    }
}
