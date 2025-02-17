<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $orders = [
            [
                'user_id' => 1, // Assuming user 1 exists
                'shop_id' => 1, // Assuming shop 1 exists
                'sub_total' => 1500.00,
                'discount' => 100.00,
                'tax' => 50.00,
                'shipping_fee' => 50.00,
                'payable' => 1500.00 - 100.00 + 50.00 + 50.00, // 1500 - 100 + 50 + 50 = 1500
                'due' => 0.00,
                'status' => 2, // Completed
            ],
            [
                'user_id' => 2,
                'shop_id' => 2,
                'sub_total' => 2500.00,
                'discount' => 200.00,
                'tax' => 100.00,
                'shipping_fee' => 60.00,
                'payable' => 2500.00 - 200.00 + 100.00 + 60.00, // 2460
                'due' => 500.00,
                'status' => 1, // Pending
            ],
            [
                'user_id' => 3,
                'shop_id' => 3,
                'sub_total' => 800.00,
                'discount' => 50.00,
                'tax' => 20.00,
                'shipping_fee' => 30.00,
                'payable' => 800.00 - 50.00 + 20.00 + 30.00, // 800
                'due' => 0.00,
                'status' => 3, // Shipped
            ],
            [
                'user_id' => 4,
                'shop_id' => 1,
                'sub_total' => 4000.00,
                'discount' => 300.00,
                'tax' => 150.00,
                'shipping_fee' => 100.00,
                'payable' => 4000.00 - 300.00 + 150.00 + 100.00, // 3950
                'due' => 3950.00,
                'status' => 4, // Canceled
            ],
            [
                'user_id' => 5,
                'shop_id' => 2,
                'sub_total' => 1200.00,
                'discount' => 80.00,
                'tax' => 40.00,
                'shipping_fee' => 25.00,
                'payable' => 1200.00 - 80.00 + 40.00 + 25.00, // 1185
                'due' => 0.00,
                'status' => 2, // Completed
            ],
        ];

        foreach ($orders as $order) {
            DB::table('orders')->insert([
                'user_id' => $order['user_id'],
                'shop_id' => $order['shop_id'],
                'sub_total' => $order['sub_total'],
                'discount' => $order['discount'],
                'tax' => $order['tax'],
                'shipping_fee' => $order['shipping_fee'],
                'payable' => $order['payable'],
                'due' => $order['due'],
                'status' => $order['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
