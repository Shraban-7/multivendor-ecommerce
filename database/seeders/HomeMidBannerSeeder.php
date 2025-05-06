<?php

namespace Database\Seeders;

use App\Models\HomeMidBanner;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomeMidBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners = [
            [
                'title' => 'Comfort coming right up now',
                'subtitle' => 'It\' s slow - cook season',
                'description' => '',
                'button_text' => 'Shop Now',
                'button_link' => '#',
                'image' => 'images/home_mid_banners/gallery-feature-pro-1-min.png',
                'is_active' => true,
                'position' => 1,
            ],
            [
                'title' => 'Beat The Chill',
                'subtitle' => 'Coat, Jackets & More',
                'description' => '',
                'button_text' => 'Shop Now',
                'button_link' => '#',
                'image' => 'images/home_mid_banners/gallery-feature-pro-2-min.png',
                'is_active' => true,
                'position' => 2,
            ],
            [
                'title' => 'Festive decor in everywhere',
                'subtitle' => '',
                'description' => '',
                'button_text' => 'Shop Now',
                'button_link' => '#',
                'image' => 'images/home_mid_banners/gallery-feature-pro-3-min.png',
                'is_active' => true,
                'position' => 3,
            ],
            [
                'title' => 'Holiday Kitchen',
                'subtitle' => '',
                'description' => '',
                'button_text' => 'Shop Now',
                'button_link' => '#',
                'image' => 'images/home_mid_banners/gallery-feature-pro-4-min.png',
                'is_active' => true,
                'position' => 4,
            ],
            [
                'title' => 'Curted Fits for the season',
                'subtitle' => '',
                'description' => '',
                'button_text' => 'Shop Now',
                'button_link' => '#',
                'image' => 'images/home_mid_banners/gallery-feature-pro-5-min.png',
                'is_active' => true,
                'position' => 5,
            ],
        ];

        foreach ($banners as $banner) {
            HomeMidBanner::create($banner);
        }
    }
}
