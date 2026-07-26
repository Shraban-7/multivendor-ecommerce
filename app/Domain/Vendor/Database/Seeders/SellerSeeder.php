<?php

namespace App\Domain\Vendor\Database\Seeders;

use App\Domain\Vendor\Models\Seller;
use App\Enums\CommissionType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SellerSeeder extends Seeder
{
    /**
     * Canonical marketplace sellers. Keys match products.json "seller" values.
     */
    public static function catalog(): array
    {
        return [
            [
                'name' => 'Urban Thread',
                'email' => 'urbanthread@gmail.com',
                'phone' => '01720000001',
                'business_address' => 'Mirpur, Dhaka',
            ],
            [
                'name' => 'Merinor',
                'email' => 'merinor@gmail.com',
                'phone' => '01720000002',
                'business_address' => 'Gulshan, Dhaka',
            ],
            [
                'name' => 'Volt Cable',
                'email' => 'voltcable@gmail.com',
                'phone' => '01720000003',
                'business_address' => 'Motijheel, Dhaka',
            ],
            [
                'name' => 'Lianoa Home',
                'email' => 'lianoahome@gmail.com',
                'phone' => '01720000004',
                'business_address' => 'Uttara, Dhaka',
            ],
            [
                'name' => 'Sole Street',
                'email' => 'solestreet@gmail.com',
                'phone' => '01720000005',
                'business_address' => 'Banani, Dhaka',
            ],
            [
                'name' => 'Little Nest',
                'email' => 'littlenest@gmail.com',
                'phone' => '01720000006',
                'business_address' => 'Dhanmondi, Dhaka',
            ],
            [
                'name' => 'Atelier Hub',
                'email' => 'atelierhub@gmail.com',
                'phone' => '01720000011',
                'business_address' => 'Elephant Road, Dhaka',
            ],
            [
                'name' => 'Green Basket',
                'email' => 'greenbasket@gmail.com',
                'phone' => '01720000012',
                'business_address' => 'Farmgate, Dhaka',
            ],
            [
                'name' => 'Circuit Lane',
                'email' => 'circuitlane@gmail.com',
                'phone' => '01720000013',
                'business_address' => 'IDB Bhaban, Dhaka',
            ],
            [
                'name' => 'Peak Athletics',
                'email' => 'peakathletics@gmail.com',
                'phone' => '01720000014',
                'business_address' => 'Bashundhara, Dhaka',
            ],
            [
                'name' => 'Loom Label',
                'email' => 'loomlabel@gmail.com',
                'phone' => '01720000015',
                'business_address' => 'New Market, Dhaka',
            ],
            [
                'name' => 'Everyday Mart',
                'email' => 'everydaymart@gmail.com',
                'phone' => '01720000016',
                'business_address' => 'Mohakhali, Dhaka',
            ],
            [
                'name' => 'Nest Co',
                'email' => 'nestco@gmail.com',
                'phone' => '01720000017',
                'business_address' => 'Tejgaon, Dhaka',
            ],
            [
                'name' => 'Glow Bar',
                'email' => 'glowbar@gmail.com',
                'phone' => '01720000018',
                'business_address' => 'Bailey Road, Dhaka',
            ],
            [
                'name' => 'Page Pine',
                'email' => 'pagepine@gmail.com',
                'phone' => '01720000019',
                'business_address' => 'Nilkhet, Dhaka',
            ],
            [
                'name' => 'Pixel Depot',
                'email' => 'pixeldepot@gmail.com',
                'phone' => '01720000020',
                'business_address' => 'Kawran Bazar, Dhaka',
            ],
            [
                'name' => 'Whisk Works',
                'email' => 'whiskworks@gmail.com',
                'phone' => '01720000021',
                'business_address' => 'Shantinagar, Dhaka',
            ],
        ];
    }

    /** Old products.json seller name → new catalog name. */
    public static function renameMap(): array
    {
        return [
            'Spinner Fashion' => 'Urban Thread',
            'Merinor' => 'Merinor',
            'Pluxio' => 'Volt Cable',
            'Lianoa' => 'Lianoa Home',
            'Sneaktra' => 'Sole Street',
            'Babee Shop' => 'Little Nest',
            'FashionHub' => 'Atelier Hub',
            'FreshMart' => 'Green Basket',
            'GadgetZone' => 'Circuit Lane',
            'SportMax' => 'Peak Athletics',
            'StyleCraft' => 'Loom Label',
            'DailyNeeds' => 'Everyday Mart',
            'HomeEase' => 'Nest Co',
            'BeautyGlow' => 'Glow Bar',
            'BookNest' => 'Page Pine',
            'TechMart' => 'Pixel Depot',
            'KitchenPro' => 'Whisk Works',
        ];
    }

    public function run(): void
    {
        foreach (self::catalog() as $seller) {
            $name = $seller['name'];
            $username = Str::slug($name);
            $logoPath = "images/{$username}/logo/{$username}-logo.jpg";

            Seller::updateOrCreate(
                ['email' => $seller['email']],
                [
                    'name' => $name,
                    'username' => $username,
                    'phone' => $seller['phone'],
                    'password' => Hash::make('password'),
                    'image' => $logoPath,
                    'business_name' => $name,
                    'business_logo' => $logoPath,
                    'business_email' => 'business.'.$seller['email'],
                    'business_address' => $seller['business_address'],
                    'shipping_cost' => 100,
                    'is_active' => 1,
                    'status' => Seller::ACTIVE,
                    'code' => Seller::generateSellerCode($name),
                    'commission_type' => CommissionType::PERCENTAGE->value,
                    'commission_amount' => 10.5,
                ]
            );
        }
    }
}
