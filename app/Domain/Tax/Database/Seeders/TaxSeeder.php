<?php

namespace App\Domain\Tax\Database\Seeders;

use App\Domain\Tax\Models\TaxClass;
use App\Domain\Tax\Models\TaxRate;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    public function run(): void
    {
        $standard = TaxClass::firstOrCreate(
            ['slug' => 'standard'],
            ['name' => 'Standard Rate', 'description' => 'Default VAT rate for most goods']
        );

        TaxRate::firstOrCreate(
            ['tax_class_id' => $standard->id, 'name' => 'VAT 15%', 'rate' => 15.00],
            ['priority' => 0, 'is_active' => true, 'country' => 'BD']
        );

        $reduced = TaxClass::firstOrCreate(
            ['slug' => 'reduced'],
            ['name' => 'Reduced Rate', 'description' => 'Reduced VAT rate for essential goods']
        );

        TaxRate::firstOrCreate(
            ['tax_class_id' => $reduced->id, 'name' => 'VAT 5%', 'rate' => 5.00],
            ['priority' => 0, 'is_active' => true, 'country' => 'BD']
        );

        $zero = TaxClass::firstOrCreate(
            ['slug' => 'zero'],
            ['name' => 'Zero Rate', 'description' => 'Zero-rated goods (e.g., basic food items)']
        );

        TaxRate::firstOrCreate(
            ['tax_class_id' => $zero->id, 'name' => 'VAT 0%', 'rate' => 0.00],
            ['priority' => 0, 'is_active' => true, 'country' => 'BD']
        );

        $exempt = TaxClass::firstOrCreate(
            ['slug' => 'exempt'],
            ['name' => 'Exempt', 'description' => 'VAT exempt goods/services']
        );

        TaxRate::firstOrCreate(
            ['tax_class_id' => $exempt->id, 'name' => 'Exempt', 'rate' => 0.00],
            ['priority' => 0, 'is_active' => true, 'country' => 'BD']
        );

        $this->command?->info('Tax classes and rates seeded successfully.');
    }
}
