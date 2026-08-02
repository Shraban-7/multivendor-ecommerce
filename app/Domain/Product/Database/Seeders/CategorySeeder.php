<?php

namespace App\Domain\Product\Database\Seeders;

use App\Domain\Product\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->truncate();

        $jsonPath = database_path('data/products.json');
        if (File::exists($jsonPath)) {
            $jsonData = json_decode(File::get($jsonPath), true);
            if (! empty($jsonData['categories'])) {
                $iconMap = [
                    'Electronics' => 'fa fa-plug-circle-bolt',
                    'Fashion' => 'fa fa-shirt',
                    'Footwear' => 'fa fa-shoe-prints',
                    'Home & Kitchen' => 'fa fa-house-chimney',
                    'Health & Beauty' => 'fa fa-wand-magic-sparkles',
                    'Sports & Outdoors' => 'fa fa-basketball',
                    'Food & Grocery' => 'fa fa-basket-shopping',
                    'Stationery & Books' => 'fa fa-book',
                    'Automobile' => 'fa fa-car-side',
                ];

                foreach ($jsonData['categories'] as $cat) {
                    $catSlug = Str::slug($cat['name']);
                    $categoryId = Category::insertGetId([
                        'name' => $cat['name'],
                        'image' => $cat['image'] ?? "storage/categories/{$catSlug}.jpg",
                        'icon' => $iconMap[$cat['name']] ?? 'fa fa-layer-group',
                        'slug' => str_slug('categories', 'slug', $cat['name']),
                        'status' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    foreach ($cat['subcategories'] ?? [] as $child) {
                        $childSlug = Str::slug($child['name']);
                        Category::insert([
                            'name' => $child['name'],
                            'icon' => 'fa fa-tag',
                            'image' => $child['image'] ?? "storage/categories/{$childSlug}.jpg",
                            'slug' => str_slug('categories', 'slug', $child['name']),
                            'category_id' => $categoryId,
                            'status' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                return;
            }
        }
    }
}
