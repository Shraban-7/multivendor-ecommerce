<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;

class FeaturedProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::with('subcategories')->get();

        $featuredThumbs = [
            'featured-video-thumb-1.png',
            'featured-video-thumb-2.png',
            'featured-video-thumb-3.png',
        ];

        $featuredVideos = [
            'video-product-1.mp4',
            'video-product-2.mp4',
            'video-product-3.mp4',
        ];

        for ($i = 1; $i <= 8; $i++) {
            $category = $categories->random();
            $subcategory = $category->subcategories->isNotEmpty()
                ? $category->subcategories->random()
                : null;

            Product::create([
                'name' => trim(($subcategory->name ?? $category->name) . ' Product ' . $i),
                'slug' => Str::slug(($subcategory->name ?? $category->name) . ' Product ' . $i),
                'thumbnail' => 'images/feature_product/thumb/' . $featuredThumbs[($i - 1) % count($featuredThumbs)],
                'short_description' => 'Short description for ' . ($subcategory->name ?? $category->name) . ' product ' . $i,
                'description' => 'Detailed description for ' . ($subcategory->name ?? $category->name) . ' product ' . $i,
                'buying_price' => rand(50, 500),
                'selling_price' => rand(100, 1000),
                'discount_type' => 'percentage',
                'discount_amount' => rand(5, 30),
                'quantity' => rand(10, 100),
                'unit_id' => rand(1, 10),
                'category_id' => $category->id,
                'subcategory_id' => $subcategory?->id,
                'brand_id' => null,
                'seller_id' => 1,
                'sku' => 'SKU-' . strtoupper(Str::random(8)),
                'status' => 1,
                'stock_status' => 'in_stock',
                'stock_in' => rand(5, 50),
                'stock_out' => rand(0, 10),
                'shipping_cost' => rand(20, 100),
                'tax' => rand(5, 15),
                'views' => rand(100, 5000),
                'is_featured' => true,
                'video' => 'videos/products/' . $featuredVideos[($i - 1) % count($featuredVideos)],
            ]);
        }
    }
}
