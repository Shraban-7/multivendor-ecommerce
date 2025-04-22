<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Tesko+',
                'is_nav' => 1,
                'subcategories' => [
                    'Tesko Fresh',
                    'Tesko Home',
                    'Tesko Fashion'
                ]
            ],
            [
                'name' => 'Toy Shop',
                'is_nav' => 1,
                'subcategories' => [
                    'Action Figures',
                    'Board Games',
                    'Outdoor Toys'
                ]
            ],
            [
                'name' => 'Halloween',
                'is_nav' => 1,
                'is_special' => 1,
                'subcategories' => [
                    'Costumes',
                    'Decorations',
                    'Party Supplies'
                ]
            ],
            [
                'name' => 'Electronics',
                'image' => 'images/categories/base/category-4.png',
                'cover_image' => 'images/categories/cover/electronics-promo.png',
                'cover_title' => 'Powering Possibilities, Empowering Lives.',
                'cover_description' => 'Discounts on living room sets, bedroom furniture, outdoor furniture, and home office desks.',
                'cover_text_color' => '#FFFFFF',
                'cover_bg_color' => '#9EB5AF',
                'cover_button_color' => '#5A422A',
                'is_nav' => 1,
                'is_slider' => 1,
                'subcategories' => [
                    'Laptops',
                    'Smartphones',
                    'Accessories'
                ]
            ],
            [
                'name' => 'Fashion',
                'image' => 'images/categories/base/category-3.png',
                'is_nav' => 1,
                'is_slider' => 1,
                'subcategories' => [
                    'Men\'s Wear',
                    'Women\'s Wear',
                    'Kids\' Fashion'
                ]
            ],
            [
                'name' => 'Grocery & Essentials',
                'image' => 'images/categories/base/category-1.png',
                'cover_image' => 'images/categories/cover/grocery-promo.png',
                'cover_title' => 'Get 50% CASHBACK ON SHOPPING $250',
                'cover_description' => 'Provides shoppers with an extensive range of groceries, from fresh produce and meats to pantry staples, snacks, and household essentials.',
                'cover_text_color' => '#FFDB9C',
                'cover_bg_color' => '#8B2022',
                'cover_button_color' => '#FD740F',
                'is_nav' => 1,
                'is_slider' => 1,
                'subcategories' => [
                    'Fruits & Vegetables',
                    'Dairy Products',
                    'Snacks & Beverages'
                ]
            ],
            [
                'name' => 'Deals',
                'is_nav' => 1,
                'subcategories' => [
                    'Daily Deals',
                    'Seasonal Sales',
                    'Clearance'
                ]
            ],
            [
                'name' => 'Clothing shoes & Accessories',
                'image' => 'images/categories/base/category-3.png',
                'is_slider' => 1
            ],
            [
                'name' => 'Toys, Kids And Baby',
            ],
            [
                'name' => 'SmartPhone',
                'subcategories' => [
                    'Apple',
                    'Samsung',
                    'Google',
                    'OnePlus',
                    'Xiaomi',
                    'Oppo',
                    'Vivo',
                    'Realme',
                    'Motorola',
                    'Asus',
                    'Huawei',
                    'Honor',
                    'Nothing',
                    'Infinix',
                    'Tecno',
                    'Feature Phones',
                ]
            ],
            [
                'name' => 'Personal Care',
                'image' => 'images/categories/base/category-5.png',
                'is_slider' => 1
            ],
            [
                'name' => 'Pharmacy, Health & Wellness',
            ],
            [
                'name' => 'Auto & Tires',
                'image' => 'images/categories/base/category-6.png',
                'is_slider' => 1
            ],
            [
                'name' => 'Household Essentials',
                'image' => 'images/categories/base/category-2.png',
                'is_slider' => 1
            ],
            [
                'name' => 'Pets',
            ],
            [
                'name' => 'Sports & Outdoors',
            ],
            [
                'name' => 'School Office & Art Supplies',
            ],
            [
                'name' => 'Movies Music & Books',
            ],
            [
                'name' => 'Gifts Card',
            ],
            [
                'name' => 'Shop With Purpose',
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
                'slug' => str_slug('categories', 'slug', $categoryData['name']),
            ]);

            if (!empty($categoryData['subcategories'])) {
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
}
