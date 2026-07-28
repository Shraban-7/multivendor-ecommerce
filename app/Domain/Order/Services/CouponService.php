<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Models\Coupon;
use App\Domain\Order\Models\Cart;
use App\Domain\Product\Models\Product;
use Illuminate\Support\Collection;

class CouponService
{
    public function validateCoupon(string $code, int $sellerId, float $subTotal, Collection $cartItems): array
    {
        $coupon = Coupon::where('code', $code)->active()->first();

        if (! $coupon) {
            return ['valid' => false, 'message' => 'Invalid or expired coupon code.'];
        }

        if (! $coupon->isGlobal() && $coupon->seller_id !== $sellerId) {
            return ['valid' => false, 'message' => 'This coupon is not valid for this seller.'];
        }

        if ($coupon->min_purchase && $subTotal < (float) $coupon->min_purchase) {
            return [
                'valid' => false,
                'message' => 'Minimum purchase amount of ' . money($coupon->min_purchase) . ' required.',
            ];
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return ['valid' => false, 'message' => 'This coupon has reached its usage limit.'];
        }

        if ($coupon->products()->exists()) {
            $validProductIds = $coupon->products()->pluck('product_id')->toArray();
            $cartProductIds = $cartItems->pluck('product_id')->toArray();
            if (! array_intersect($validProductIds, $cartProductIds)) {
                return ['valid' => false, 'message' => 'This coupon is not applicable to items in your cart.'];
            }
        }

        $discount = $this->calculateDiscount($subTotal, $coupon);

        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'message' => 'Coupon applied successfully!',
        ];
    }

    public function calculateDiscount(float $subTotal, Coupon $coupon): float
    {
        $value = (float) $coupon->discount_value;

        $discount = match ($coupon->discount_type) {
            'percentage' => ($subTotal * $value) / 100,
            default => $value,
        };

        if ($coupon->max_discount && $discount > (float) $coupon->max_discount) {
            $discount = (float) $coupon->max_discount;
        }

        return round(min($discount, $subTotal), 2);
    }

    public function apply(Coupon $coupon): void
    {
        $coupon->increment('used_count');
    }

    public function release(Coupon $coupon): void
    {
        if ($coupon->used_count > 0) {
            $coupon->decrement('used_count');
        }
    }
}
