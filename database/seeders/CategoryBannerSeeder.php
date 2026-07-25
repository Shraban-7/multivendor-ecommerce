<?php

namespace Database\Seeders;

use App\Models\CategoryBanner;
use Illuminate\Database\Seeder;

class CategoryBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'image' => 'frontend/images/sessional-promo-1.png',
                'category_id' => 3,
            ],
            [
                'image' => 'frontend/images/sessional-promo-2.jpg',
                'category_id' => 3,
            ],
            [
                'image' => 'frontend/images/sessional-promo-3.jpg',
                'category_id' => 3,
            ],
            [
                'image' => 'frontend/images/sessional-promo-4.png',
                'category_id' => 3,
            ],
            [
                'image' => 'frontend/images/sessional-promo-5.jpeg',
                'category_id' => 3,
            ],
        ];

        foreach ($banners as $banner) {
            CategoryBanner::insert([
                'image' => $banner['image'],
                'category_id' => $banner['category_id'],
            ]);
        }
    }
}
