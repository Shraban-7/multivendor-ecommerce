<?php

namespace App\Domain\Product\Database\Seeders;

use App\Domain\Product\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Brand::truncate();

        $brands = [
            ['name' => 'Samsung', 'slug' => 'samsung', 'image' => 'images/brands/brand-1.png'],
            ['name' => 'Apple', 'slug' => 'apple', 'image' => 'images/brands/brand-2.png'],
            ['name' => 'Sony', 'slug' => 'sony', 'image' => 'images/brands/brand-3.png'],
            ['name' => 'Nike', 'slug' => 'nike', 'image' => 'images/brands/brand-4.png'],
            ['name' => 'Adidas', 'slug' => 'adidas', 'image' => 'images/brands/brand-5.png'],
            ['name' => 'LG', 'slug' => 'lg', 'image' => 'images/brands/brand-6.png'],
            ['name' => 'Dell', 'slug' => 'dell', 'image' => 'images/brands/brand-7.png'],
            ['name' => 'HP', 'slug' => 'hp', 'image' => 'images/brands/brand-8.png'],
            ['name' => 'Surpass', 'slug' => 'surpass', 'image' => 'images/brands/surpass-logo.png'],
            ['name' => 'Zara', 'slug' => 'zara', 'image' => 'images/brands/zara-logo.png'],
        ];

        foreach ($brands as $brand) {
            Brand::insert([
                'name' => $brand['name'],
                'slug' => $brand['slug'],
                'image' => $brand['image'],
            ]);
        }
    }
}
