<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Electronics',
                'image' => 'frontend/images/category-1.png',
                'subcategories' => ['Mobile Phones', 'Laptops', 'Cameras', 'Headphones', 'Smart Watches']
            ],
            [
                'name' => 'Fashion',
                'image' => 'frontend/images/category-2.png',
                'subcategories' => ['Men\'s Clothing', 'Women\'s Clothing', 'Shoes', 'Accessories', 'Jewelry']
            ],
            [
                'name' => 'Grocery & Essentials',
                'image' => 'frontend/images/category-3.png',
                'subcategories' => ['Oil', 'Vegetable', 'Drinks', 'Meat', 'Rice']
            ],
            [
                'name' => 'Beauty & Health',
                'image' => 'frontend/images/category-4.png',
                'subcategories' => ['Makeup', 'Skincare', 'Hair Care', 'Personal Care', 'Health Supplements']
            ],
            [
                'name' => 'Sports & Outdoors',
                'image' => 'frontend/images/category-5.png',
                'subcategories' => ['Fitness Equipment', 'Camping Gear', 'Sports Apparel', 'Bikes', 'Outdoor Furniture']
            ],
            [
                'name' => 'Toys & Games',
                'image' => 'frontend/images/category-6.png',
                'subcategories' => ['Action Figures', 'Board Games', 'Educational Toys', 'Outdoor Toys', 'Dolls & Stuffed Animals']
            ],
            [
                'name' => 'Books & Stationery',
                'image' => 'frontend/images/category-1.png',
                'subcategories' => ['Fiction', 'Non-fiction', 'Academic Books', 'Stationery', 'Arts & Crafts']
            ],
            [
                'name' => 'Automotive',
                'image' => 'frontend/images/category-2.png',
                'subcategories' => ['Car Accessories', 'Motorcycle Accessories', 'Tools & Equipment', 'Car Electronics', 'Tires & Wheels']
            ],
            [
                'name' => 'Groceries',
                'image' => 'frontend/images/category-3.png',
                'subcategories' => ['Fresh Produce', 'Snacks & Beverages', 'Dairy & Eggs', 'Frozen Foods', 'Packaged Goods']
            ],
            [
                'name' => 'Books & Media',
                'image' => 'frontend/images/category-4.png',
                'subcategories' => ['Fiction', 'Non-fiction', 'Children\'s Books', 'Magazines', 'E-books']
            ],
        ];

        foreach ($categories as $categoryData) {
            $categoryId = DB::table('categories')->insertGetId([
                'name' => $categoryData['name'],
                'image' => $categoryData['image'],
                'slug' => str_slug('categories','slug', $categoryData['name']),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            foreach ($categoryData['subcategories'] as $subcategory) {
                Category::insert([
                    'name' => $subcategory,
                    'slug' => str_slug('categories', 'slug', $subcategory),
                    'category_id' => $categoryId,
                ]);
            }
        }
    }
}


