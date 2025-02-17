<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run()
    {
        $coupons = [
            [
                'code' => 'SUMMER20',
                'discount_type' => 'percentage',
                'discount_value' => 20.00, // 20% off
                'min_purchase' => 100.00, // Minimum purchase of 100
                'max_discount' => 50.00, // Maximum discount of 50
                'valid_from' => now(), // Always valid
                'valid_until' => now()->addYear(), // Valid for 1 year
                'usage_limit' => 100, // Can be used 100 times
                'used_count' => 0, // No usage yet
                'status' => 1, // Active
            ],
            [
                'code' => 'FLAT50',
                'discount_type' => 'flat',
                'discount_value' => 50.00, // 50 flat discount
                'min_purchase' => 150.00, // Minimum purchase of 150
                'max_discount' => null, // No maximum discount
                'valid_from' => now(), // Always valid
                'valid_until' => now()->addYear(), // Valid for 1 year
                'usage_limit' => 200, // Can be used 200 times
                'used_count' => 0, // No usage yet
                'status' => 1, // Active
            ],
            [
                'code' => 'NEWYEAR15',
                'discount_type' => 'percentage',
                'discount_value' => 15.00, // 15% off
                'min_purchase' => 50.00, // Minimum purchase of 50
                'max_discount' => null, // No maximum discount
                'valid_from' => now(), // Always valid
                'valid_until' => now()->addMonth(), // Valid for 1 month
                'usage_limit' => 300, // Can be used 300 times
                'used_count' => 0, // No usage yet
                'status' => 1, // Active
            ],
        ];

        foreach ($coupons as $coupon) {
            DB::table('coupons')->insert([
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
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
