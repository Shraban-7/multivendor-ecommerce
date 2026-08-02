<?php

namespace App\Domain\Product\Database\Seeders;

use App\Domain\Product\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        Banner::query()->delete();

        $banners = [
            // Hero slider
            [
                'image' => '/banners/banner-1.png',
                'section' => Banner::SECTION_HERO,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'image' => '/banners/banner-2.png',
                'section' => Banner::SECTION_HERO,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'image' => '/banners/banner-3.png',
                'section' => Banner::SECTION_HERO,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'image' => '/banners/banner-4.png',
                'section' => Banner::SECTION_HERO,
                'is_active' => true,
                'sort_order' => 4,
            ],

            // Side category panels
            [
                'image' => '/banners/banner-6.png',
                'section' => Banner::SECTION_CATEGORY_TOP,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'image' => '/banners/banner-7.png',
                'section' => Banner::SECTION_CATEGORY_TOP,
                'is_active' => true,
                'sort_order' => 2,
            ],

            // Mid promo strip
            [
                'title' => "Mega Savings\nThis Week",
                'subtitle' => 'Special Campaign',
                'description' => 'Exclusive discounts from top sellers — shop before stocks run out.',
                'image' => '/banners/banner-4.png',
                'button_text' => 'Check Offers',
                'button_link' => '/products',
                'section' => Banner::SECTION_MID_PROMO,
                'is_active' => true,
                'sort_order' => 1,
            ],

            // Flash sale banner
            [
                'title' => 'Flash Sale Live Now',
                'subtitle' => 'Up to 50% Off',
                'description' => 'Grab the hottest deals before the timer runs out.',
                'image' => '/banners/banner-5.png',
                'button_text' => 'Shop Flash Sale',
                'button_link' => '/flash-sales',
                'section' => Banner::SECTION_FLASH_SALE,
                'is_active' => true,
                'sort_order' => 1,
            ],

            // Footer banner
            [
                'title' => 'Become a Seller',
                'subtitle' => 'Grow With Shob Cart',
                'description' => 'Reach millions of customers and grow your business online.',
                'image' => '/banners/banner-1.png',
                'button_text' => 'Start Selling',
                'button_link' => '/seller-signup',
                'section' => Banner::SECTION_FOOTER_BANNER,
                'is_active' => true,
                'sort_order' => 1,
            ],

            // Promo modal
            [
                'title' => 'Welcome Offer',
                'subtitle' => 'New Here?',
                'description' => 'Enjoy exclusive deals on your first order.',
                'image' => '/banners/banner-5.png',
                'button_text' => 'Claim Offer',
                'button_link' => '/products',
                'section' => Banner::SECTION_PROMO_MODAL,
                'is_active' => true,
                'sort_order' => 1,
            ],
        ];

        $now = now();

        foreach ($banners as $banner) {
            Banner::create([
                ...$banner,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $this->command?->info('Seeded '.count($banners).' banners with /banners/*.png images.');
    }
}
