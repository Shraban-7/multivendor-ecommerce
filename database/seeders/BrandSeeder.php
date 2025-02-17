<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Samsung', 'slug' => 'samsung', 'image' => 'frontend/images/category-1.png'],
            ['name' => 'Apple', 'slug' => 'apple', 'image' => 'frontend/images/category-2.png'],
            ['name' => 'Sony', 'slug' => 'sony', 'image' => 'frontend/images/category-3.png'],
            ['name' => 'Nike', 'slug' => 'nike', 'image' => 'frontend/images/category-4.png'],
            ['name' => 'Adidas', 'slug' => 'adidas', 'image' => 'frontend/images/category-5.png'],
            ['name' => 'LG', 'slug' => 'lg', 'image' => 'frontend/images/category-6.png'],
            ['name' => 'Dell', 'slug' => 'dell', 'image' => 'frontend/images/category-5.png'],
            ['name' => 'HP', 'slug' => 'hp', 'image' => 'frontend/images/category-3.png'],
            ['name' => 'Xiaomi', 'slug' => 'xiaomi', 'image' => 'frontend/images/category-2.png'],
            ['name' => 'Puma', 'slug' => 'puma', 'image' => 'frontend/images/category-4.png'],
        ];

        foreach ($brands as $brand) {
            DB::table('brands')->insert([
                'name' => $brand['name'],
                'slug' => $brand['slug'],
                'image' => $brand['image'], // You can replace this with actual image paths or URLs if necessary
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
