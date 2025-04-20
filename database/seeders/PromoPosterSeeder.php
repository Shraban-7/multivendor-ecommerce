<?php

namespace Database\Seeders;

use App\Models\PromoPoster;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromoPosterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posters = [
            [
                'title' => 'Comfort coming right up now',
                'link' => '#',
                'image' => 'images/promo_posters/hero-image-2.png',
                'is_active' => true,
                'position' => 1,
            ],
            [
                'title' => 'Big Save On Deals',
                'link' => '#',
                'image' => 'images/promo_posters/promo-fifty.png',
                'is_active' => true,
                'position' => 2,
            ],
        ];

        foreach ($posters as $poster) {
            PromoPoster::create($poster);
        }
    }
}
