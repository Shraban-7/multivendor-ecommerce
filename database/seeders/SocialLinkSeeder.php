<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use DB;
use Illuminate\Database\Seeder;

class SocialLinkSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('social_links')->truncate();

        $links = [
            [
                'name' => 'Facebook',
                'icon_name' => 'fa-facebook-f',
                'link' => 'https://facebook.com/yourpage',
                'color' => 'blue',
            ],
            [
                'name' => 'Instagram',
                'icon_name' => 'fa-instagram',
                'link' => 'https://instagram.com/yourprofile',
                'color' => 'pink',
            ],
            [
                'name' => 'LinkedIn',
                'icon_name' => 'fa-linkedin-in',
                'link' => 'https://linkedin.com/in/yourprofile',
                'color' => 'blue',
            ],
            [
                'name' => 'YouTube',
                'icon_name' => 'fa-youtube',
                'link' => 'https://youtube.com/yourchannel',
                'color' => 'red',
            ],
        ];

        foreach ($links as $link) {
            SocialLink::create([
                'name' => $link['name'],
                'icon_name' => $link['icon_name'],
                'link' => $link['link'],
                'color' => $link['color'],
                'status' => true,
            ]);
        }
    }
}
