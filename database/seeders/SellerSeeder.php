<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellers = [
            [
                'fullname' => 'Seller User',
                'image' => '/images/sellers/avatar/provider-logo-1.png',
                'email' => 'seller@example.com',
                'phone' => '12345679',
                'password' => 'password',
                'business_name' => 'Louis Vuitton',
                'business_logo' => '/images/sellers/business/provider-logo-1.png',
                'business_email' => 'louis_vuitton@example.com',
                'business_address' => 'Lorem ipsum dolor sit amet',
                'country_id' => 2,
                'zip' => '1400',
                'shipping_cost' => 20
            ],
            [
                'fullname' => 'Seller User 2',
                'image' => '/images/sellers/avatar/provider-logo-1.png',
                'email' => 'seller2@example.com',
                'phone' => '12345670',
                'password' => 'password',
                'business_name' => 'Ranger',
                'business_logo' => '/images/sellers/business/provider-logo-2.png',
                'business_email' => 'ranger@example.com',
                'business_address' => 'Lorem ipsum dolor sit amet',
                'country_id' => 3,
                'zip' => '1200',
                'shipping_cost' => 30
            ],
        ];

        foreach ($sellers as $seller) {
            $seller['username'] = str_slug('sellers', 'username', $seller['fullname']);
            Seller::create($seller);
        }
    }
}
