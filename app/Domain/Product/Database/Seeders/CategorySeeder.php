<?php

namespace App\Domain\Product\Database\Seeders;

use App\Domain\Product\Models\Category;
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
                'icon' => 'fa fa-shirt',
                'image' => 'images/categories/base/category-3-min.png',
                'children' => [
                    ['name' => 'Men’s Fashion', 'icon' => 'fa fa-person'],
                    ['name' => 'Women’s Fashion', 'icon' => 'fa fa-person-dress'],
                    ['name' => 'Kids & Babies', 'icon' => 'fa fa-child'],
                ],
            ],

            [
                'name' => 'Footwear',
                'icon' => 'fa fa-shoe-prints',
                'image' => 'images/categories/base/category-2-min.png',
                'children' => [
                    ['name' => 'Men’s Footwear', 'icon' => 'fa fa-shoe-prints'],
                    ['name' => 'Women’s Footwear', 'icon' => 'fa fa-shoe-prints'],
                    ['name' => 'Kids Footwear', 'icon' => 'fa fa-shoe-prints'],
                ],
            ],

            [
                'name' => 'Beauty & Personal Care',
                'icon' => 'fa fa-wand-magic-sparkles',
                'image' => 'images/categories/base/category-7-min.png',
                'children' => [
                    ['name' => 'Skincare', 'icon' => 'fa fa-spa'],
                    ['name' => 'Haircare', 'icon' => 'fa fa-wind'],
                    ['name' => 'Makeup', 'icon' => 'fa fa-paintbrush'],
                    ['name' => 'Fragrances', 'icon' => 'fa fa-spray-can-sparkles'],
                    ['name' => 'Men’s Grooming', 'icon' => 'fa fa-hand-sparkles'],
                ],
            ],

            [
                'name' => 'Electronics',
                'icon' => 'fa fa-plug-circle-bolt',
                'image' => 'images/categories/base/category-6-min.png',
                'children' => [
                    ['name' => 'Mobile Accessories', 'icon' => 'fa fa-mobile-screen-button'],
                    ['name' => 'Computer Accessories', 'icon' => 'fa fa-computer'],
                    ['name' => 'Gadgets', 'icon' => 'fa fa-microchip'],
                    ['name' => 'Audio & Video', 'icon' => 'fa fa-headphones'],
                ],
                'child' => [
                    ['name' => 'Earphone', 'icon' => 'fa fa-ear-listen'],
                    ['name' => 'Bluetooth Speaker', 'icon' => 'fa fa-volume-high'],
                    ['name' => 'Charger', 'icon' => 'fa fa-bolt'],
                ],
            ],

            [
                'name' => 'Home & Living',
                'icon' => 'fa fa-house-chimney',
                'image' => 'images/categories/base/category-4-min.png',
                'children' => [
                    ['name' => 'Home Decor', 'icon' => 'fa fa-couch'],
                    ['name' => 'Kitchen & Dining', 'icon' => 'fa fa-utensils'],
                    ['name' => 'Gifts & Novelties', 'icon' => 'fa fa-gift'],
                ],
            ],

            [
                'name' => 'Toys, Kids & Baby',
                'icon' => 'fa fa-puzzle-piece',
                'image' => 'images/categories/base/category-8-min.png',
                'children' => [
                    ['name' => 'Action Figures', 'icon' => 'fa-robot'],
                    ['name' => 'Board Games & Puzzles', 'icon' => 'fa fa-chess-board'],
                ],
                'child' => [
                    ['name' => 'Educational Toys', 'icon' => 'fa fa-lightbulb'],
                    ['name' => 'Outdoor Toys', 'icon' => 'fa fa-tree'],
                    ['name' => 'Puzzles', 'icon' => 'fa fa-jigsaw'],
                ],
            ],

            [
                'name' => 'Sports & Outdoors',
                'icon' => 'fa fa-basketball',
                'image' => 'images/categories/base/category-9-min.png',
                'children' => [
                    ['name' => 'Fitness Equipment', 'icon' => 'fa fa-dumbbell'],
                    ['name' => 'Team Sports', 'icon' => 'fa fa-football'],
                    ['name' => 'Outdoor Gear', 'icon' => 'fa fa-campground'],
                    ['name' => 'Cycling', 'icon' => 'fa fa-bicycle'],
                ],
            ],

            [
                'name' => 'Automobile',
                'icon' => 'fa fa-car-side',
                'image' => 'images/categories/base/category-10-min.png',
                'children' => [
                    ['name' => 'Cars', 'icon' => 'fa fa-car'],
                    ['name' => 'Car Accessories', 'icon' => 'fa fa-gears'],
                    ['name' => 'Motorcycle Parts', 'icon' => 'fa fa-motorcycle'],
                ],
                'child' => [
                    ['name' => 'Engine Oils', 'icon' => 'fa fa-oil-can'],
                    ['name' => 'Tires & Wheels', 'icon' => 'fa fa-circle-notch'],
                    ['name' => 'Tools', 'icon' => 'fa fa-screwdriver-wrench'],
                ],
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
                'status' => true,
            ]);

            if (! empty($categoryData['children'])) {
                foreach ($categoryData['children'] as $child) {
                    Category::insert([
                        'name' => $child['name'],
                        'icon' => $child['icon'],
                        'slug' => str_slug('categories', 'slug', $child['name']),
                        'category_id' => $categoryId,
                    ]);
                }
            }

            if (! empty($categoryData['child'])) {
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
