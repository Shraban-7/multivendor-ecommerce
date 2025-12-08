<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Wishlist;
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

            $cartCount = 0;
            $sub_total = 0;
            $grand_total = 0;
            $wishlistCount = 0;

            if (Auth::check()) {

                // Cart Calculation
                $carts = Cart::where('user_id', Auth::id())
                    ->with('cart_items.product')
                    ->get();

                foreach ($carts as $cart) {
                    foreach ($cart->cart_items as $item) {
                        $item_total = $item->quantity * $item->price;
                        $sub_total += $item_total;
                        $grand_total += $item_total;
                        $cartCount++;
                    }
                }

                // Wishlist Count
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

            } else {
                $carts = collect();
                $wishlistCount = 0;
            }

            $view->with('cartCount', $cartCount)
                ->with('totalPrice', $grand_total)
                ->with('subTotal', $sub_total)
                ->with('wishlistCount', $wishlistCount);
        });
    }

}
