<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run()
    {
        $coupons = [
            [
                'code' => 'SUMMER20',
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'min_purchase' => 100.00,
                'max_discount' => 50.00,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'usage_limit' => 100,
                'used_count' => 0,
                'status' => 1,
            ],
            [
                'code' => 'FLAT50',
                'discount_type' => 'flat',
                'discount_value' => 50.00,
                'min_purchase' => 150.00,
                'max_discount' => null,
                'valid_from' => now(),
                'valid_until' => now()->addYear(),
                'usage_limit' => 200,
                'used_count' => 0,
                'status' => 1,
            ],
            [
                'code' => 'NEWYEAR15',
                'discount_type' => 'percentage',
                'discount_value' => 15.00,
                'min_purchase' => 50.00,
                'max_discount' => null,
                'valid_from' => now(),
                'valid_until' => now()->addMonth(),
                'usage_limit' => 300,
                'used_count' => 0,
                'status' => 1,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::insert([
                'code' => $coupon['code'],
                'discount_type' => $coupon['discount_type'],
                'discount_value' => $coupon['discount_value'],
                'min_purchase' => $coupon['min_purchase'],
                'max_discount' => $coupon['max_discount'],
                'valid_from' => $coupon['valid_from'],
                'valid_until' => $coupon['valid_until'],
                'usage_limit' => $coupon['usage_limit'],
                'used_count' => $coupon['used_count'],
                'status' => $coupon['status'],
            ]);
        }
    }
}
