<?php

namespace Database\Seeders;

use App\Models\HeroBanner;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class HeroBannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banners =[
            [
                'title' => 'Nike Air Max',
                'subtitle' => 'Nike introduction the new air max for everyone\'s comfort',
                'description' => 'Discover amazing products and services tailored just for you.',
                'button_text' => 'Shop Now',
                'button_link' => '#',
                'image' => 'images/hero_banners/hero-image-1-min.png',
                'is_active' => true,
                'position' => 1,
            ],
            [
                'title' => 'Become Our Sellers',
                'subtitle' => 'Every users can be a seller',
                'description' => '',
                'button_text' => 'Get Started',
                'button_link' => '#',
                'image' => 'images/hero_banners/hero-image-2-min.png',
                'is_active' => true,
                'position' => 2,
            ],
            [
                'title' => 'Stroller',
                'subtitle' => 'collection',
                'description' => 'fresh arrival',
                'button_text' => '',
                'button_link' => '#',
                'image' => 'images/hero_banners/hero-image-3-min.png',
                'is_active' => true,
                'position' => 3,
            ],
            [
                'title' => '30% off',
                'subtitle' => 'handbags, travel kits, day packs & more',
                'description' => 'Your fashion journey start here',
                'button_text' => 'Grab yours now',
                'button_link' => '#',
                'image' => 'images/hero_banners/hero-image-4-min.png',
                'is_active' => true,
                'position' => 4,
            ],
            [
                'title' => 'Door prize',
                'subtitle' => 'start from 17-19 june',
                'description' => 'Terms and condition on caption',
                'button_text' => '',
                'button_link' => '#',
                'image' => 'images/hero_banners/hero-image-5-min.jpg',
                'is_active' => true,
                'position' => 5,
            ],
        ];

        foreach($banners as $banner)
        {
            HeroBanner::create($banner);
        }
    }
}
