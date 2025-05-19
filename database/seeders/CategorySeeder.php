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
                'name' => 'Skin Care',
                'image' => 'images/categories/base/category-5-min.png',
                'cover_image' => 'images/categories/cover/skin-care-promo-min.png',
                'cover_title' => 'Pamper Your Skin, Pamper Yourself',
                'cover_description' => 'The retailer has worked to improve its fashion offerings in recent years, adding more trendy, sustainable, and inclusive pieces.',
                'cover_text_color' => '#FFFFFF',
                'cover_bg_color' => '#D0AB6A',
                'cover_button_color' => '#FFB321',
                'is_nav' => 1,
                'subcategories' => [
                    'Cleansers',
                    'Moisturizers',
                    'Sunscreens',
                    'Serums & Treatments',
                    'Face Masks',
                    'Eye Creams',
                ]
            ],
            [
                'name' => 'Automotive',
                'image' => 'images/categories/base/category-6-min.png',
                'cover_image' => 'images/categories/cover/automotive-promo-min.png',
                'cover_title' => 'You, asked for it. You got it, Toyota',
                'cover_description' => 'Whether you need replacement parts, performance upgrades, or maintenance essentials, you can often find great offers.',
                'cover_text_color' => '#FFFFFF',
                'cover_bg_color' => '#334161',
                'cover_button_color' => '#10387D',
                'is_nav' => 1,
                'subcategories' => [
                    'Car Accessories',
                    'Motorcycle Parts',
                    'Engine Oils & Fluids',
                    'Tires & Wheels',
                    'Interior Accessories',
                    'Tools & Equipment',
                ]
            ],
            [
                'name' => 'Electronics',
                'image' => 'images/categories/base/category-4-min.png',
                'cover_image' => 'images/categories/cover/electronics-promo-min.png',
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
                'image' => 'images/categories/base/category-3-min.png',
                'cover_image' => 'images/categories/cover/fashion-promo-min.png',
                'cover_title' => 'Get 50% CASHBACK ON SHOPPING $250',
                'cover_description' => 'The retailer has worked to improve its fashion offerings in recent years, adding more trendy, sustainable, and inclusive pieces.',
                'cover_text_color' => '#FFDB9C',
                'cover_bg_color' => '#3A2732',
                'cover_button_color' => '#0DA487',
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
                'image' => 'images/categories/base/category-1-min.png',
                'cover_image' => 'images/categories/cover/grocery-promo-min.png',
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
                'name' => 'Home Appliances',
                'image' => 'images/categories/base/category-5-min.png',
                'cover_image' => 'images/categories/cover/home-appliances-promo-min.png',
                'cover_title' => 'Revolutionize your home with our modern appliances',
                'cover_description' => 'The retailer has worked to improve its fashion offerings in recent years, adding more trendy, sustainable, and inclusive pieces.',
                'cover_text_color' => '#FFFFFF',
                'cover_bg_color' => '#3F4C50',
                'cover_button_color' => '#0DA487',
                'is_nav' => 1,
                'subcategories' => [
                    'Refrigerators',
                    'Washing Machines',
                    'Microwaves',
                    'Air Conditioners',
                    'Vacuum Cleaners',
                    'Water Purifiers',
                ]
            ],
            [
                'name' => 'Clothing shoes & Accessories',
                'image' => 'images/categories/base/category-3-min.png',
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
                'image' => 'images/categories/base/category-5-min.png',
                'is_slider' => 1
            ],
            [
                'name' => 'Pharmacy, Health & Wellness',
            ],
            [
                'name' => 'Auto & Tires',
                'image' => 'images/categories/base/category-6-min.png',
                'is_slider' => 1
            ],
            [
                'name' => 'Household Essentials',
                'image' => 'images/categories/base/category-2-min.png',
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
