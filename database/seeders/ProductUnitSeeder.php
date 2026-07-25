<?php

namespace Database\Seeders;

use App\Domain\Product\Models\ProductUnit;
use Illuminate\Database\Seeder;

class ProductUnitSeeder extends Seeder
{
    public function run()
    {
        $units = [
            ['name' => 'Piece', 'short_name' => 'pc'],
            ['name' => 'Kilogram', 'short_name' => 'kg'],
            ['name' => 'Gram', 'short_name' => 'g'],
            ['name' => 'Liter', 'short_name' => 'L'],
            ['name' => 'Milliliter', 'short_name' => 'mL'],
            ['name' => 'Pack', 'short_name' => 'pk'],
            ['name' => 'Box', 'short_name' => 'bx'],
            ['name' => 'Dozen', 'short_name' => 'dz'],
            ['name' => 'Meter', 'short_name' => 'm'],
            ['name' => 'Centimeter', 'short_name' => 'cm'],
        ];

        foreach ($units as $unit) {
            ProductUnit::insert([
                'name' => $unit['name'],
                'short_name' => $unit['short_name'],
            ]);
        }
    }
}
