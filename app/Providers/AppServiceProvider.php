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
            $carts = Cart::where('user_id', Auth::user()->id)
            ->with('cartItems.product')
            ->get()
            ->groupBy(function ($cart) {
                return $cart->cartItems->first()->product->seller_id ?? null;
            });
            $cartCount = count($carts);
            $sub_total = 0;
            $grand_total =0 ;
            $totalPrice = 0;
            foreach ($carts as $seller_id => $cartGroup) {
                foreach ($cartGroup as $cart) {
                    foreach ($cart->cartItems as $item) {
                        $item_grand_total = $item->quantity * $item->product->selling_price;
                        $grand_total += $item_grand_total;

                        if ($item->product->discount_type != null) {
                            if ($item->product->discount_type == DiscountType::FLAT) {
                                $item_sub_total = $item->quantity * ($item->product->selling_price - $item->product->discount_amount);
                            } elseif ($item->product->discount_type == DiscountType::PERCENTAGE) {
                                $item_sub_total = $item->quantity * ($item->product->selling_price - ($item->product->selling_price * $item->product->discount_amount) / 100);
                            }
                        } else {
                            $item_sub_total = $item_grand_total;
                        }

                        $sub_total += $item_sub_total;
                    }
                }
            }

            $totalPrice = $grand_total;

            $view->with('cartCount', $cartCount)->with('totalPrice', $totalPrice);
        });
    }
}
