<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryOptionSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                "category" => "Fashion & Clothing",
                "subcategories" => [
                    "Men's Fashion",
                    "Women's Fashion",
                    "Kids & Babies",
                    "T-Shirts & Polos"
                ],
                "options" => ["Size", "Color"]
            ],
            [
                "category" => "Men's Fashion",
                "subcategories" => [],
                "options" => ["Size", "Color"]
            ],
            [
                "category" => "Women's Fashion",
                "subcategories" => [],
                "options" => ["Size", "Color"]
            ],
            [
                "category" => "Kids & Babies",
                "subcategories" => [],
                "options" => ["Size", "Color"]
            ],
            [
                "category" => "T-Shirts & Polos",
                "subcategories" => [],
                "options" => ["Size", "Color"]
            ],

            [
                "category" => "Footwear",
                "subcategories" => [
                    "Men's Footwear",
                    "Women's Footwear",
                    "Kids Footwear",
                    "Sliders"
                ],
                "options" => ["Size", "Color"]
            ],
            [
                "category" => "Men's Footwear",
                "subcategories" => [],
                "options" => ["Size", "Color"]
            ],
            [
                "category" => "Women's Footwear",
                "subcategories" => [],
                "options" => ["Size", "Color"]
            ],
            [
                "category" => "Kids Footwear",
                "subcategories" => [],
                "options" => ["Size", "Color"]
            ],
            [
                "category" => "Sliders",
                "subcategories" => [],
                "options" => ["Size", "Color"]
            ],

            [
                "category" => "Beauty & Personal Care",
                "subcategories" => [
                    "Skincare",
                    "Haircare",
                    "Makeup",
                    "Fragrances",
                    "Men's Grooming"
                ],
                "options" => ["Volume", "Shade", "Fragrance"]
            ],
            [
                "category" => "Skincare",
                "subcategories" => [],
                "options" => ["Volume"]
            ],
            [
                "category" => "Haircare",
                "subcategories" => [],
                "options" => ["Volume"]
            ],
            [
                "category" => "Makeup",
                "subcategories" => [],
                "options" => ["Shade"]
            ],
            [
                "category" => "Fragrances",
                "subcategories" => [],
                "options" => ["Volume"]
            ],
            [
                "category" => "Men's Grooming",
                "subcategories" => [],
                "options" => ["Volume"]
            ],

            [
                "category" => "Electronics",
                "subcategories" => [
                    "Mobile Accessories",
                    "Computer Accessories",
                    "Gadgets",
                    "Audio & Video",
                    "Cable",
                    "Earphone (Wired)",
                    "Neckband",
                    "Ear Buds/TWS",
                    "Bluetooth Speaker",
                    "USB Adapter",
                    "Charger"
                ],
                "options" => ["Color", "Storage", "Region"]
            ],
            [
                "category" => "Mobile Accessories",
                "subcategories" => [],
                "options" => ["Color"]
            ],
            [
                "category" => "Computer Accessories",
                "subcategories" => [],
                "options" => ["Color"]
            ],
            [
                "category" => "Gadgets",
                "subcategories" => [],
                "options" => ["Color", "Storage", "Region"]
            ],
            [
                "category" => "Audio & Video",
                "subcategories" => [],
                "options" => ["Color"]
            ],
            [
                "category" => "Cable",
                "subcategories" => [],
                "options" => ["Length", "Color"]
            ],
            [
                "category" => "Earphone (Wired)",
                "subcategories" => [],
                "options" => ["Color"]
            ],
            [
                "category" => "Neckband",
                "subcategories" => [],
                "options" => ["Color"]
            ],
            [
                "category" => "Ear Buds/TWS",
                "subcategories" => [],
                "options" => ["Color"]
            ],
            [
                "category" => "Bluetooth Speaker",
                "subcategories" => [],
                "options" => ["Color"]
            ],
            [
                "category" => "USB Adapter",
                "subcategories" => [],
                "options" => ["Color"]
            ],
            [
                "category" => "Charger",
                "subcategories" => [],
                "options" => ["Color"]
            ],

            [
                "category" => "Home & Living",
                "subcategories" => [
                    "Home Decor",
                    "Gifts & Novelties",
                    "Kitchen & Dining"
                ],
                "options" => ["Color", "Size"]
            ],
            [
                "category" => "Home Decor",
                "subcategories" => [],
                "options" => ["Color", "Size"]
            ],
            [
                "category" => "Gifts & Novelties",
                "subcategories" => [],
                "options" => ["Color"]
            ],
            [
                "category" => "Kitchen & Dining",
                "subcategories" => [],
                "options" => ["Color", "Size"]
            ],

            [
                "category" => "Kids And Baby",
                "subcategories" => [
                    "Action Figures",
                    "Board Games",
                    "Educational Toys",
                    "Outdoor Toys",
                    "Puzzles"
                ],
                "options" => ["Color", "Size"]
            ],
            [
                "category" => "Action Figures",
                "subcategories" => [],
                "options" => ["Character", "Size", "Color"]
            ],
            [
                "category" => "Board Games",
                "subcategories" => [],
                "options" => ["Edition"]
            ],
            [
                "category" => "Educational Toys",
                "subcategories" => [],
                "options" => ["Color", "Size"]
            ],
            [
                "category" => "Outdoor Toys",
                "subcategories" => [],
                "options" => ["Color", "Size"]
            ],
            [
                "category" => "Puzzles",
                "subcategories" => [],
                "options" => ["Pieces Count"]
            ],

            [
                "category" => "Sports & Outdoors",
                "subcategories" => [
                    "Fitness Equipment",
                    "Team Sports",
                    "Outdoor Gear",
                    "Cycling"
                ],
                "options" => ["Size", "Color"]
            ],
            [
                "category" => "Fitness Equipment",
                "subcategories" => [],
                "options" => ["Weight", "Color"]
            ],
            [
                "category" => "Team Sports",
                "subcategories" => [],
                "options" => ["Size", "Color"]
            ],
            [
                "category" => "Outdoor Gear",
                "subcategories" => [],
                "options" => ["Size", "Color"]
            ],
            [
                "category" => "Cycling",
                "subcategories" => [],
                "options" => ["Frame Size", "Color"]
            ],

            [
                "category" => "Automobile",
                "subcategories" => [
                    "Cars",
                    "Car Accessories",
                    "Motorcycle Parts",
                    "Engine Oils & Fluids",
                    "Tires & Wheels",
                    "Interior Accessories",
                    "Tools & Equipment"
                ],
                "options" => ["Color", "Variant", "Region"]
            ],
            [
                "category" => "Cars",
                "subcategories" => [],
                "options" => ["Color", "Variant", "Region"]
            ],
            [
                "category" => "Car Accessories",
                "subcategories" => [],
                "options" => ["Color"]
            ],
            [
                "category" => "Motorcycle Parts",
                "subcategories" => [],
                "options" => ["Color"]
            ],
            [
                "category" => "Engine Oils & Fluids",
                "subcategories" => [],
                "options" => ["Volume"]
            ],
            [
                "category" => "Tires & Wheels",
                "subcategories" => [],
                "options" => ["Size"]
            ],
            [
                "category" => "Interior Accessories",
                "subcategories" => [],
                "options" => ["Color"]
            ],
            [
                "category" => "Tools & Equipment",
                "subcategories" => [],
                "options" => ["Size"]
            ]
        ];

        foreach ($categories as $cat) {
            $category = Category::where('name', $cat['category'])->first();

            if ($category) {
                foreach ($cat['options'] as $optionName) {
                    $option = Option::firstOrCreate(['name' => $optionName]);
                    $category->options()->syncWithoutDetaching([$option->id]);
                }
            }
        }
    }
}
