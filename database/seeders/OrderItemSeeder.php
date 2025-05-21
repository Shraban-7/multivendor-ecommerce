<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        $orderItems = [
            [
                'order_id' => 1, // Assuming order 1 exists
                'product_id' => 1, // Assuming product 1 exists
                'product_variant' => '8GB RAM, 128GB Storage',
                'product_variant_price' => 200.00,
                'buying_price' => 500.00,
                'unit_price' => 700.00,
                'quantity' => 2,
                'discount' => 50.00,
                'sub_total' => (700.00 * 2) - 50.00, // 1400 - 50 = 1350
            ],
            [
                'order_id' => 1,
                'product_id' => 2,
                'product_variant' => 'Black, 256GB SSD',
                'product_variant_price' => 100.00,
                'buying_price' => 800.00,
                'unit_price' => 1000.00,
                'quantity' => 1,
                'discount' => 100.00,
                'sub_total' => (1000.00 * 1) - 100.00, // 900
            ],
            [
                'order_id' => 2,
                'product_id' => 3,
                'product_variant' => '4K Camera Lens',
                'product_variant_price' => 150.00,
                'buying_price' => 1200.00,
                'unit_price' => 1500.00,
                'quantity' => 1,
                'discount' => 200.00,
                'sub_total' => (1500.00 * 1) - 200.00, // 1300
            ],
            [
                'order_id' => 3,
                'product_id' => 4,
                'product_variant' => 'Red, Size M',
                'product_variant_price' => 20.00,
                'buying_price' => 50.00,
                'unit_price' => 70.00,
                'quantity' => 3,
                'discount' => 30.00,
                'sub_total' => (70.00 * 3) - 30.00, // 210 - 30 = 180
            ],
            [
                'order_id' => 4,
                'product_id' => 5,
                'product_variant' => 'Leather Material',
                'product_variant_price' => 50.00,
                'buying_price' => 300.00,
                'unit_price' => 400.00,
                'quantity' => 1,
                'discount' => 20.00,
                'sub_total' => (400.00 * 1) - 20.00, // 380
            ],
        ];

        foreach ($orderItems as $item) {
            OrderItem::create([
                'order_id' => $item['order_id'],
                'product_id' => $item['product_id'],
                // 'product_variant' => $item['product_variant'],
                'product_variant_price' => $item['product_variant_price'],
                'buying_price' => $item['buying_price'],
                'unit_price' => $item['unit_price'],
                'quantity' => $item['quantity'],
                'discount' => $item['discount'],
                'sub_total' => $item['sub_total'],
            ]);
        }
    }
}
