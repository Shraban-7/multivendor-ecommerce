<?php

namespace App\Domain\Product\Database\Seeders;

use App\Domain\Product\Models\Size;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SizeSeeder extends Seeder
{
    public function run(): void
    {
        $sizes = [
            'XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL',
            '34', '36', '38', '40', '42', '44', '46', '48',
            'Free Size',
        ];

        foreach ($sizes as $index => $name) {
            Size::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
