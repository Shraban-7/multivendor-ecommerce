<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::category()->with('subcategories')->get();

        $thumbnails = [
            "images/products/grocery-prod-1.png",
            "images/products/grocery-prod-2.png",
            "images/products/grocery-prod-3.png",
            "images/products/grocery-prod-4.png",
            "images/products/grocery-prod-5.png",
            "images/products/grocery-prod-6.png",
            "images/products/grocery-prod-7.png",
            "images/products/grocery-prod-8.png",
            "images/products/electronic-prod-1.png",
            "images/products/electronic-prod-2.png",
            "images/products/electronic-prod-3.png",
            "images/products/electronic-prod-4.png",
            "images/products/electronic-prod-5.png",
            "images/products/electronic-prod-6.png",
            "images/products/electronic-prod-7.png",
            "images/products/electronic-prod-8.png",
        ];

        $units = [
            "KG",
            "G",
            "Piece",
            "Pair"
        ];

        foreach ($categories as $category) {
            if (!empty($category->subcategories)) {
                foreach ($category->subcategories as $subcategory) {
                    for ($i = 1; $i <= 8; $i++) {
                        Product::create([
                            'name' =>trim($subcategory->name .' Product '. $i) ,
                            'slug' => Str::slug(trim($subcategory->name . ' ' . $category->name . ' Product ' . $i)),
                            'thumbnail' => $thumbnails[array_rand($thumbnails)],
                            'short_description' => 'Short description for ' . $subcategory->name . ' ' . substr($category->name, 0, 3) . ' product ' . $i,
                            'description' => 'Detailed description for ' . $subcategory->name . ' ' . substr($category->name, 0, 3) . ' product ' . $i,
                            'buying_price' => rand(50, 500),
                            'selling_price' => rand(100, 1000),
                            'discount_type' => 'percentage',
                            'discount_amount' => rand(5, 30),
                            'quantity' => rand(10, 100),
                            'unit' => rand(1, 100) . $units[array_rand($units)],
                            'category_id' => $category->id,
                            'subcategory_id' => $subcategory->id,
                            'brand_id' => null,
                            'seller_id' => 1,
                            'sku' => 'SKU-' . strtoupper(Str::random(8)),
                            'barcode' => 'BAR-' . strtoupper(Str::random(10)),
                            'status' => 1,
                            'stock_status' => 'in_stock',
                            'stock_in' => rand(5, 50),
                            'stock_out' => rand(0, 10),
                            'shipping_cost' => rand(20, 100),
                            'tax' => rand(5, 15),
                            'views' => rand(100, 5000),
                        ]);
                    }
                }
            } else {
                for ($i = 1; $i <= 8; $i++) {
                    Product::create([
                        'name' => substr($category->name, 0, 3) . ' Product ' . $i,
                        'slug' => Str::slug(substr($category->name, 0, 3) . '-product-' . $i),
                        'thumbnail' => $thumbnails[array_rand($thumbnails)],
                        'short_description' => 'Short description for ' . substr($category->name, 0, 3) . ' product ' . $i,
                        'description' => 'Detailed description for ' . substr($category->name, 0, 3) . ' product ' . $i,
                        'buying_price' => rand(50, 500),
                        'selling_price' => rand(100, 1000),
                        'discount_type' => 'percentage',
                        'discount_amount' => rand(5, 30),
                        'quantity' => rand(10, 100),
                        'unit' => rand(1, 100) . $units[array_rand($units)],
                        'category_id' => $category->id,
                        'brand_id' => null,
                        'seller_id' => 1,
                        'sku' => 'SKU-' . strtoupper(Str::random(8)),
                        'barcode' => 'BAR-' . strtoupper(Str::random(10)),
                        'status' => 1,
                        'stock_status' => 'in_stock',
                        'stock_in' => rand(5, 50),
                        'stock_out' => rand(0, 10),
                        'shipping_cost' => rand(20, 100),
                        'tax' => rand(5, 15),
                        'views' => rand(100, 5000),
                    ]);
                }
            }
        }
    }
}
