<?php

use App\Domain\Order\Models\Coupon;
use App\Domain\Order\Services\CartService;

test('cart service calculates percentage coupon discount', function () {
    $service = new CartService;
    $coupon = new Coupon([
        'discount_type' => 'percent',
        'discount_amount' => 10,
    ]);

    $discount = $service->applyCouponDiscount(1000, $coupon);

    expect($discount)->toBe(100.0);
});

test('cart service calculates fixed coupon discount capped at subtotal', function () {
    $service = new CartService;
    $coupon = new Coupon([
        'discount_type' => 'fixed',
        'discount_amount' => 500,
    ]);

    expect($service->applyCouponDiscount(200, $coupon))->toBe(200.0);
});
