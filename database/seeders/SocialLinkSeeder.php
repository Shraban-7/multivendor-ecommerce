<?php

namespace Database\Seeders;

use App\Models\SocialLink;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $links = [
            [
                'name' => 'Facebook',
                'icon_name' => 'fa-facebook-f',
                'link' => 'https://facebook.com/yourpage',
            ],
            [
                'name' => 'Twitter',
                'icon_name' => 'fa-x-twitter',
                'link' => 'https://twitter.com/yourhandle',
            ],
            [
                'name' => 'Instagram',
                'icon_name' => 'fa-instagram',
                'link' => 'https://instagram.com/yourprofile',
            ],
            [
                'name' => 'LinkedIn',
                'icon_name' => 'fa-linkedin-in',
                'link' => 'https://linkedin.com/in/yourprofile',
            ],
            [
                'name' => 'YouTube',
                'icon_name' => 'fa-youtube',
                'link' => 'https://youtube.com/yourchannel',
            ],
        ];

        foreach ($links as $link) {
            SocialLink::create([
                'name' => $link['name'],
                'icon_name' => $link['icon_name'],
                'link' => $link['link'],
                'status' => true,
            ]);
        }
    }
}
