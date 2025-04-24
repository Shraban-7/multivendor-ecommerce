<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeOption;
use Illuminate\Database\Seeder;

class ProductAttributeSeeder extends Seeder
{
    public function run()
    {
        $attributes = ['Color', 'Size', 'Storage', 'Material'];

        foreach ($attributes as $name) {
            ProductAttribute::firstOrCreate(['name' => $name]);
        }
    }
}
