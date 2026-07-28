<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Models\Cart;
use App\Domain\Order\Models\CartItem;
use App\Domain\Order\Models\Coupon;
use Illuminate\Support\Collection;

class CartService
{
    /**
     * @return array{sub_total: float, discount: float, total: float, items: Collection}
     */
    public function calculateTotals(Cart $cart, ?Coupon $coupon = null): array
    {
        $cart->loadMissing('cart_items.product', 'cart_items.variant.color', 'cart_items.variant.size');

        $subTotal = 0.0;
        foreach ($cart->cart_items as $item) {
            /** @var CartItem $item */
            $unit = (float) ($item->price ?? $item->discounted_price ?? 0);
            $subTotal += $unit * (int) $item->quantity;
        }

        $discount = 0.0;
        if ($coupon) {
            $discount = $this->applyCouponDiscount($subTotal, $coupon);
        }

        return [
            'sub_total' => round($subTotal, 2),
            'discount' => round($discount, 2),
            'total' => round(max(0, $subTotal - $discount), 2),
            'items' => $cart->cart_items,
        ];
    }

    public function applyCouponDiscount(float $subTotal, Coupon $coupon): float
    {
        $value = (float) $coupon->discount_value;

        if ($coupon->discount_type === 'percentage') {
            $discount = ($subTotal * $value) / 100;
        } else {
            $discount = $value;
        }

        if ($coupon->max_discount && $discount > (float) $coupon->max_discount) {
            $discount = (float) $coupon->max_discount;
        }

        return round(min($discount, $subTotal), 2);
    }
}
