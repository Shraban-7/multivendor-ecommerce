<?php

namespace App\Domain\Vendor\Database\Seeders;

use App\Domain\Vendor\Models\SellerExpenseCategory;
use Illuminate\Database\Seeder;

class SellerExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Rent',
            'Electricity Bill',
            'Internet Bill',
            'Water Bill',
            'Shop Maintenance',
            'Staff Salary',
            'Staff Meals & Refreshments',
            'Staff Bonus',
            'Product Purchases',
            'Shopping Bags',
            'Packaging Boxes',
            'Price Tags & Labels',
            'Billing Roll & Thermal Paper',
            'Advertising & Promotion',
            'Software Subscription',
            'Website Hosting',
            'Cleaning Supplies',
            'Courier Charges',
            'Equipment Repairs',
        ];

        foreach ($categories as $category) {
            SellerExpenseCategory::firstOrCreate(['name' => $category]);
        }
    }
}
