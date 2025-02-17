<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = [
            [
                'name' => 'Samsung Galaxy S21',
                'slug' => Str::slug('Samsung Galaxy S21'),
                'thumbnail' => 'images/products/electronic-prod-3.png',
                'short_desc' => 'Latest Samsung Galaxy S21 with a powerful camera and fast performance.',
                'description' => 'The Samsung Galaxy S21 offers high-end performance with a stunning display and great camera quality.',
                'buying_price' => 700.00,
                'selling_price' => 750.00,
                'discount_type' => 'percentage',
                'discount_amount' => 10.00,
                'quantity' => 50,
                'category_id' => 1, // assuming Electronics category
                'brand_id' => 1, // assuming Samsung brand
                'shop_id' => 1, // assuming Shop 1
                'sku' => 'SGS21-001',
                'barcode' => '1234567890123',
                'top_rated' => 1,
                'best_selling' => 1,
                'featured' => 1,
                'status' => 1,
                'stock_status' => 'in_stock',
                'stock_in' => 50,
                'stock_out' => 0,
                'shipping_cost' => 5.00,
                'tax' => 12.00,
                'views' => 2000,
                'meta_title' => 'Samsung Galaxy S21 - Latest Smartphone',
                'meta_keywords' => 'Samsung, Galaxy S21, Smartphone, Electronics',
                'meta_desc' => 'Buy the latest Samsung Galaxy S21 at an affordable price with a special discount.',
            ],
            [
                'name' => 'Apple iPhone 13',
                'slug' => Str::slug('Apple iPhone 13'),
                'thumbnail' => 'images/products/electronic-prod-4.png',
                'short_desc' => 'The latest iPhone 13 with enhanced features and performance.',
                'description' => 'The iPhone 13 comes with the latest Apple chip, an improved camera, and a sleek design.',
                'buying_price' => 800.00,
                'selling_price' => 850.00,
                'discount_type' => 'fixed',
                'discount_amount' => 50.00,
                'quantity' => 30,
                'category_id' => 1, // Electronics category
                'brand_id' => 2, // Apple brand
                'shop_id' => 1, // Shop 1
                'sku' => 'IP13-001',
                'barcode' => '9876543210987',
                'top_rated' => 1,
                'best_selling' => 1,
                'featured' => 1,
                'status' => 1,
                'stock_status' => 'in_stock',
                'stock_in' => 30,
                'stock_out' => 0,
                'shipping_cost' => 5.00,
                'tax' => 12.00,
                'views' => 1500,
                'meta_title' => 'Apple iPhone 13 - Latest Smartphone',
                'meta_keywords' => 'iPhone, Apple, Smartphone, Electronics',
                'meta_desc' => 'Get the latest iPhone 13 at a discounted price with free shipping.',
            ],
            [
                'name' => 'Sony WH-1000XM4 Headphones',
                'slug' => Str::slug('Sony WH-1000XM4 Headphones'),
                'thumbnail' => 'images/products/electronic-prod-5.png',
                'short_desc' => 'Noise-canceling headphones with excellent sound quality.',
                'description' => 'The Sony WH-1000XM4 offers top-tier noise-canceling features and amazing sound quality.',
                'buying_price' => 250.00,
                'selling_price' => 300.00,
                'discount_type' => 'percentage',
                'discount_amount' => 15.00,
                'quantity' => 20,
                'category_id' => 1, // Electronics category
                'brand_id' => 3, // Sony brand
                'shop_id' => 1, // Shop 1
                'sku' => 'WH1000XM4-001',
                'barcode' => '1112223334445',
                'top_rated' => 1,
                'best_selling' => 1,
                'featured' => 1,
                'status' => 1,
                'stock_status' => 'in_stock',
                'stock_in' => 20,
                'stock_out' => 0,
                'shipping_cost' => 5.00,
                'tax' => 8.00,
                'views' => 1000,
                'meta_title' => 'Sony WH-1000XM4 Noise-Canceling Headphones',
                'meta_keywords' => 'Sony, Headphones, Noise-canceling, Electronics',
                'meta_desc' => 'Buy Sony WH-1000XM4 headphones with premium sound quality and active noise cancellation.',
            ],
            [
                'name' => 'Nike Air Max 90 Shoes',
                'slug' => Str::slug('Nike Air Max 90 Shoes'),
                'thumbnail' => 'images/products/feature-product-2.png',
                'short_desc' => 'Stylish and comfortable sneakers from Nike.',
                'description' => 'The Nike Air Max 90 offers comfort and durability with a stylish design.',
                'buying_price' => 90.00,
                'selling_price' => 120.00,
                'discount_type' => 'percentage',
                'discount_amount' => 10.00,
                'quantity' => 40,
                'category_id' => 2, // Fashion category
                'brand_id' => 4, // Nike brand
                'shop_id' => 2, // Shop 2
                'sku' => 'AM90-001',
                'barcode' => '7778889990001',
                'top_rated' => 1,
                'best_selling' => 0,
                'featured' => 0,
                'status' => 1,
                'stock_status' => 'in_stock',
                'stock_in' => 40,
                'stock_out' => 0,
                'shipping_cost' => 7.00,
                'tax' => 10.00,
                'views' => 900,
                'meta_title' => 'Nike Air Max 90 Sneakers - Comfortable and Stylish',
                'meta_keywords' => 'Nike, Air Max 90, Shoes, Fashion',
                'meta_desc' => 'Shop Nike Air Max 90 sneakers for comfort and style with free shipping.',
            ],
            [
                'name' => 'Adidas Ultraboost 21',
                'slug' => Str::slug('Adidas Ultraboost 21'),
                'thumbnail' => 'images/products/feature-product-1.png',
                'short_desc' => 'Ultimate comfort and performance sneakers from Adidas.',
                'description' => 'The Adidas Ultraboost 21 provides unmatched comfort and support for everyday wear.',
                'buying_price' => 120.00,
                'selling_price' => 150.00,
                'discount_type' => 'percentage',
                'discount_amount' => 10.00,
                'quantity' => 35,
                'category_id' => 2, // Fashion category
                'brand_id' => 5, // Adidas brand
                'shop_id' => 2, // Shop 2
                'sku' => 'UB21-001',
                'barcode' => '1122334455667',
                'top_rated' => 1,
                'best_selling' => 0,
                'featured' => 0,
                'status' => 1,
                'stock_status' => 'in_stock',
                'stock_in' => 35,
                'stock_out' => 0,
                'shipping_cost' => 7.00,
                'tax' => 12.00,
                'views' => 800,
                'meta_title' => 'Adidas Ultraboost 21 - Ultimate Comfort',
                'meta_keywords' => 'Adidas, Ultraboost 21, Sneakers, Fashion',
                'meta_desc' => 'Get the Adidas Ultraboost 21 for maximum comfort and support with free shipping.',
            ],
            // More products can be added following this pattern
        ];

        foreach ($products as $product) {
            DB::table('products')->insert([
                'name' => $product['name'],
                'slug' => $product['slug'],
                'thumbnail' => $product['thumbnail'],
                'short_desc' => $product['short_desc'],
                'description' => $product['description'],
                'buying_price' => $product['buying_price'],
                'selling_price' => $product['selling_price'],
                'discount_type' => $product['discount_type'],
                'discount_amount' => $product['discount_amount'],
                'quantity' => $product['quantity'],
                'category_id' => $product['category_id'],
                'brand_id' => $product['brand_id'],
                'shop_id' => $product['shop_id'],
                'sku' => $product['sku'],
                'barcode' => $product['barcode'],
                'top_rated' => $product['top_rated'],
                'best_selling' => $product['best_selling'],
                'featured' => $product['featured'],
                'status' => $product['status'],
                'stock_status' => $product['stock_status'],
                'stock_in' => $product['stock_in'],
                'stock_out' => $product['stock_out'],
                'shipping_cost' => $product['shipping_cost'],
                'tax' => $product['tax'],
                'views' => $product['views'],
                'meta_title' => $product['meta_title'],
                'meta_keywords' => $product['meta_keywords'],
                'meta_desc' => $product['meta_desc'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
