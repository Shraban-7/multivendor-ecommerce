<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->truncate();
        // Master category list with icons + subcategories + children
        $categories = [
            [
                'name' => 'Fashion & Clothing',
                'icon' => 'fa-shirt',
                'image' => 'images/categories/base/category-3-min.png',
                'children' => [
                    ['name' => 'Men’s Fashion', 'icon' => 'fa-person'],
                    ['name' => 'Women’s Fashion', 'icon' => 'fa-person-dress'],
                    ['name' => 'Kids & Babies', 'icon' => 'fa-child'],
                ]
            ],

            [
                'name' => 'Footwear',
                'icon' => 'fa-shoe-prints',
                'image' => 'images/categories/base/category-2-min.png',
                'children' => [
                    ['name' => 'Men’s Footwear', 'icon' => 'fa-shoe-prints'],
                    ['name' => 'Women’s Footwear', 'icon' => 'fa-shoe-prints'],
                    ['name' => 'Kids Footwear', 'icon' => 'fa-shoe-prints'],
                ]
            ],

            [
                'name' => 'Beauty & Personal Care',
                'icon' => 'fa-wand-magic-sparkles',
                'image' => 'images/categories/base/category-7-min.png',
                'children' => [
                    ['name' => 'Skincare', 'icon' => 'fa-spa'],
                    ['name' => 'Haircare', 'icon' => 'fa-wind'],
                    ['name' => 'Makeup', 'icon' => 'fa-paintbrush'],
                    ['name' => 'Fragrances', 'icon' => 'fa-spray-can-sparkles'],
                    ['name' => 'Men’s Grooming', 'icon' => 'fa-hand-sparkles'],
                ]
            ],

            [
                'name' => 'Electronics',
                'icon' => 'fa-plug-circle-bolt',
                'image' => 'images/categories/base/category-6-min.png',
                'children' => [
                    ['name' => 'Mobile Accessories', 'icon' => 'fa-mobile-screen-button'],
                    ['name' => 'Computer Accessories', 'icon' => 'fa-computer'],
                    ['name' => 'Gadgets', 'icon' => 'fa-microchip'],
                    ['name' => 'Audio & Video', 'icon' => 'fa-headphones'],
                ],
                'child' => [
                    ['name' => 'Earphone', 'icon' => 'fa-ear-listen'],
                    ['name' => 'Bluetooth Speaker', 'icon' => 'fa-volume-high'],
                    ['name' => 'Charger', 'icon' => 'fa-bolt']
                ]
            ],

            [
                'name' => 'Home & Living',
                'icon' => 'fa-house-chimney',
                'image' => 'images/categories/base/category-4-min.png',
                'children' => [
                    ['name' => 'Home Decor', 'icon' => 'fa-couch'],
                    ['name' => 'Kitchen & Dining', 'icon' => 'fa-utensils'],
                    ['name' => 'Gifts & Novelties', 'icon' => 'fa-gift'],
                ]
            ],

            [
                'name' => 'Toys, Kids & Baby',
                'icon' => 'fa-puzzle-piece',
                'image' => 'images/categories/base/category-8-min.png',
                'children' => [
                    ['name' => 'Action Figures', 'icon' => 'fa-robot'],
                    ['name' => 'Board Games & Puzzles', 'icon' => 'fa-chess-board'],
                ],
                'child' => [
                    ['name' => 'Educational Toys', 'icon' => 'fa-lightbulb'],
                    ['name' => 'Outdoor Toys', 'icon' => 'fa-tree'],
                    ['name' => 'Puzzles', 'icon' => 'fa-jigsaw'],
                ]
            ],

            [
                'name' => 'Sports & Outdoors',
                'icon' => 'fa-basketball',
                'image' => 'images/categories/base/category-9-min.png',
                'children' => [
                    ['name' => 'Fitness Equipment', 'icon' => 'fa-dumbbell'],
                    ['name' => 'Team Sports', 'icon' => 'fa-futbol'],
                    ['name' => 'Outdoor Gear', 'icon' => 'fa-campground'],
                    ['name' => 'Cycling', 'icon' => 'fa-bicycle'],
                ]
            ],

            [
                'name' => 'Automobile',
                'icon' => 'fa-car-side',
                'image' => 'images/categories/base/category-10-min.png',
                'children' => [
                    ['name' => 'Cars', 'icon' => 'fa-car'],
                    ['name' => 'Car Accessories', 'icon' => 'fa-gears'],
                    ['name' => 'Motorcycle Parts', 'icon' => 'fa-motorcycle'],
                ],
                'child' => [
                    ['name' => 'Engine Oils', 'icon' => 'fa-oil-can'],
                    ['name' => 'Tires & Wheels', 'icon' => 'fa-circle-notch'],
                    ['name' => 'Tools', 'icon' => 'fa-screwdriver-wrench'],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {

            $categoryId = Category::insertGetId([
                'name' => $categoryData['name'],
                'image' => $categoryData['image'] ?? null,
                'cover_image' => $categoryData['cover_image'] ?? null,
                'cover_bg_color' => $categoryData['cover_bg_color'] ?? null,
                'cover_title' => $categoryData['cover_title'] ?? null,
                'cover_description' => $categoryData['cover_description'] ?? null,
                'cover_text_color' => $categoryData['cover_text_color'] ?? null,
                'cover_button_color' => $categoryData['cover_button_color'] ?? null,
                'is_nav' => $categoryData['is_nav'] ?? 0,
                'is_special' => $categoryData['is_special'] ?? 0,
                'is_slider' => $categoryData['is_slider'] ?? 0,
                'icon' => $categoryData['icon'] ?? null,
                'slug' => str_slug('categories', 'slug', $categoryData['name']),
                'status' => true
            ]);

            if (!empty($categoryData['children'])) {
                foreach ($categoryData['children'] as $child) {
                    Category::insert([
                        'name' => $child['name'],
                        'icon' => $child['icon'],
                        'slug' => str_slug('categories', 'slug', $child['name']),
                        'category_id' => $categoryId,
                    ]);
                }
            }

            if (!empty($categoryData['child'])) {
                foreach ($categoryData['child'] as $subchild) {
                    Category::insert([
                        'name' => $subchild['name'],
                        'icon' => $subchild['icon'],
                        'slug' => str_slug('categories', 'slug', $subchild['name']),
                        'category_id' => $categoryId,
                    ]);
                }
            }
        }
    }
}
