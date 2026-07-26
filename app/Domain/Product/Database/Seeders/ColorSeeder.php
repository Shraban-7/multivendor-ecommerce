<?php

namespace App\Domain\Product\Database\Seeders;

use App\Domain\Product\Models\Color;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Black', 'hex_code' => '#000000'],
            ['name' => 'White', 'hex_code' => '#FFFFFF'],
            ['name' => 'Red', 'hex_code' => '#EF4444'],
            ['name' => 'Blue', 'hex_code' => '#3B82F6'],
            ['name' => 'Green', 'hex_code' => '#22C55E'],
            ['name' => 'Yellow', 'hex_code' => '#EAB308'],
            ['name' => 'Navy', 'hex_code' => '#1E3A5F'],
            ['name' => 'Grey', 'hex_code' => '#6B7280'],
            ['name' => 'Brown', 'hex_code' => '#BE185D'],
            ['name' => 'Beige', 'hex_code' => '#D4B896'],
            ['name' => 'Brown', 'hex_code' => '#A855F7'],
            ['name' => 'Orange', 'hex_code' => '#F97316'],
        ];

        foreach ($colors as $color) {
            Color::firstOrCreate(
                ['slug' => Str::slug($color['name'])],
                [
                    'name' => $color['name'],
                    'hex_code' => $color['hex_code'],
                ]
            );
        }
    }
}
