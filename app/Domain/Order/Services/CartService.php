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
        $cart->loadMissing('cart_items.product', 'cart_items.variant');

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
        $type = $coupon->discount_type ?? $coupon->type ?? 'fixed';
        $value = (float) ($coupon->discount_amount ?? $coupon->amount ?? 0);

        if (in_array($type, ['percent', 'percentage', 1, '1'], true)) {
            return min($subTotal, ($subTotal * $value) / 100);
        }

        return min($subTotal, $value);
    }
}
