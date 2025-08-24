<?php

namespace Database\Seeders;

use App\Enums\CommissionType;
use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SellerSeeder extends Seeder
{
    public function run()
    {
        $sellers = [
            [
                'name'              => 'Spinner Fashion',
                'image'             => 'spinner-fashion-logo.jpg',
                'email'             => 'spinnerfashion@gmail.com',
                'phone'             => '01720000001',
                'password'          => Hash::make('password'),
                'business_name'     => 'Spinner Fashion',
                'business_logo'     => 'spinner-fashion-logo.jpg',
                'business_email'    => 'business.spinnerfashion@gmail.com',
                'business_address'  => 'Dhaka',
                'shipping_cost'     => 100,
                'is_active'         => 1,
                'commission_type'   => CommissionType::PERCENTAGE->value,
                'commission_amount' => 10.5,
            ],
            [
                'name'              => 'Merinor',
                'image'             => 'merinor-logo.jpg',
                'email'             => 'merinor@gmail.com',
                'phone'             => '01720000001',
                'password'          => Hash::make('password'),
                'business_name'     => 'Merinor',
                'business_logo'     => 'merinor-logo.jpg',
                'business_email'    => 'business.merinor@gmail.com',
                'business_address'  => 'Dhaka',
                'shipping_cost'     => 100,
                'is_active'         => 1,
                'commission_type'   => CommissionType::PERCENTAGE->value,
                'commission_amount' => 10.5,
            ],
            [
                'name'              => 'Pluxio',
                'image'             => 'pluxio-logo.jpg',
                'email'             => 'pluxio@gmail.com',
                'phone'             => '01720000001',
                'password'          => Hash::make('password'),
                'business_name'     => 'Pluxio',
                'business_logo'     => 'pluxio-logo.jpg',
                'business_email'    => 'business.pluxio@gmail.com',
                'business_address'  => 'Dhaka',
                'shipping_cost'     => 100,
                'is_active'         => 1,
                'commission_type'   => CommissionType::PERCENTAGE->value,
                'commission_amount' => 10.5,
            ],
            [
                'name'              => 'Lianoa',
                'image'             => 'lianoa-logo.jpg',
                'email'             => 'lianoa@gmail.com',
                'phone'             => '01720000001',
                'password'          => Hash::make('password'),
                'business_name'     => 'Lianoa',
                'business_logo'     => 'lianoa-logo.jpg',
                'business_email'    => 'business.lianoa@gmail.com',
                'business_address'  => 'Dhaka',
                'shipping_cost'     => 100,
                'is_active'         => 1,
                'commission_type'   => CommissionType::PERCENTAGE->value,
                'commission_amount' => 10.5,
            ],
            [
                'name'              => 'Sneaktra',
                'image'             => 'sneaktra-logo.jpg',
                'email'             => 'sneaktra@gmail.com',
                'phone'             => '01720000001',
                'password'          => Hash::make('password'),
                'business_name'     => 'Sneaktra',
                'business_logo'     => 'sneaktra-logo.jpg',
                'business_email'    => 'business.sneaktra@gmail.com',
                'business_address'  => 'Dhaka',
                'shipping_cost'     => 100,
                'is_active'         => 1,
                'commission_type'   => CommissionType::PERCENTAGE->value,
                'commission_amount' => 10.5,
            ],
            [
                'name'              => 'Babee Shop',
                'image'             => 'babee-shop-logo.jpg',
                'email'             => 'babeeshop@gmail.com',
                'phone'             => '01720000001',
                'password'          => Hash::make('password'),
                'business_name'     => 'Babee Shop',
                'business_logo'     => 'babee-shop-logo.jpg',
                'business_email'    => 'business.babeeshop@gmail.com',
                'business_address'  => 'Dhaka',
                'shipping_cost'     => 100,
                'is_active'         => 1,
                'commission_type'   => CommissionType::PERCENTAGE->value,
                'commission_amount' => 10.5,
            ],
        ];

        foreach ($sellers as $seller) {
            $username = Str::slug($seller['name']);
            $seller['username'] = $username;
            $seller['image'] = "images/{$username}/logo/{$seller['image']}";
            $seller['business_logo'] = "images/{$username}/logo/{$seller['business_logo']}";
            
            Seller::create($seller);
        }
    }

    public function run_old(): void
    {
        $sellers = [
            [
                'name'                => 'Unilever Seller',
                'image'               => '/images/sellers/avatar/provider-logo-1.png',
                'email'               => 'unilever.seller@gmail.com',
                'phone'               => '01720000001',
                'password'            => Hash::make('password'),
                'business_name'       => 'Unilever Bangladesh Ltd',
                'business_logo'       => '/images/sellers/business/unilever-logo.png',
                'business_email'      => 'business.unilever@gmail.com',
                'business_address'    => 'Tejgaon I/A, Dhaka',
                'shipping_cost'       => 25,
                'trade_license_no'    => 'UL-TL-2023001',
                'trade_license_image' => '/images/sellers/licenses/unilever-license.jpg',
                'shop_image'          => '/images/sellers/shops/unilever-shop.jpg',
                'nid_no'              => '1976543210',
                'nid_front_image'     => '/images/sellers/nids/unilever-nid-front.jpg',
                'nid_back_image'      => '/images/sellers/nids/unilever-nid-back.jpg',
                'is_active'           => 1,
                'commission_type'     => CommissionType::PERCENTAGE->value,
                'commission_amount'   => 10.5,
            ],
            [
                'name'                => 'Nestlé Partner',
                'image'               => '/images/sellers/avatar/provider-logo-2.png',
                'email'               => 'nestle.seller@gmail.com',
                'phone'               => '01720000002',
                'password'            => Hash::make('password'),
                'business_name'       => 'Nestlé Bangladesh',
                'business_logo'       => '/images/sellers/business/nestle-logo.png',
                'business_email'      => 'business.nestle@gmail.com',
                'business_address'    => 'Kawran Bazar, Dhaka',
                'shipping_cost'       => 20,
                'trade_license_no'    => 'NE-TL-2023002',
                'trade_license_image' => '/images/sellers/licenses/nestle-license.jpg',
                'shop_image'          => '/images/sellers/shops/nestle-shop.jpg',
                'nid_no'              => '1987654321',
                'nid_front_image'     => '/images/sellers/nids/nestle-nid-front.jpg',
                'nid_back_image'      => '/images/sellers/nids/nestle-nid-back.jpg',
                'is_active'           => 1,
                'commission_type'     => CommissionType::FLAT->value,
                'commission_amount'   => 30,
            ],
            [
                'name'                => 'Marico Agent',
                'image'               => '/images/sellers/avatar/provider-logo-3.png',
                'email'               => 'marico.seller@gmail.com',
                'phone'               => '01720000003',
                'password'            => Hash::make('password'),
                'business_name'       => 'Marico Ltd',
                'business_logo'       => '/images/sellers/business/marico-logo.png',
                'business_email'      => 'business.marico@gmail.com',
                'business_address'    => 'Gulshan, Dhaka',
                'shipping_cost'       => 15,
                'trade_license_no'    => 'MA-TL-2023003',
                'trade_license_image' => '/images/sellers/licenses/marico-license.jpg',
                'shop_image'          => '/images/sellers/shops/marico-shop.jpg',
                'nid_no'              => '1998765432',
                'nid_front_image'     => '/images/sellers/nids/marico-nid-front.jpg',
                'nid_back_image'      => '/images/sellers/nids/marico-nid-back.jpg',
                'is_active'           => 1,
                'commission_type'     => CommissionType::PERCENTAGE->value,
                'commission_amount'   => 8,
            ],
            [
                'name'                => 'Aarong Vendor',
                'image'               => '/images/sellers/avatar/provider-logo-4.png',
                'email'               => 'aarong.seller@gmail.com',
                'phone'               => '01720000004',
                'password'            => Hash::make('password'),
                'business_name'       => 'Aarong',
                'business_logo'       => '/images/sellers/business/aarong-logo.png',
                'business_email'      => 'business.aarong@gmail.com',
                'business_address'    => 'Uttara, Dhaka',
                'shipping_cost'       => 35,
                'trade_license_no'    => 'AR-TL-2023004',
                'trade_license_image' => '/images/sellers/licenses/aarong-license.jpg',
                'shop_image'          => '/images/sellers/shops/aarong-shop.jpg',
                'nid_no'              => '1965432189',
                'nid_front_image'     => '/images/sellers/nids/aarong-nid-front.jpg',
                'nid_back_image'      => '/images/sellers/nids/aarong-nid-back.jpg',
                'is_active'           => 1,
                'commission_type'     => CommissionType::FLAT->value,
                'commission_amount'   => 20,
            ],
            [
                'name'                => 'Walton Distributor',
                'image'               => '/images/sellers/avatar/provider-logo-5.png',
                'email'               => 'walton.seller@gmail.com',
                'phone'               => '01720000005',
                'password'            => Hash::make('password'),
                'business_name'       => 'Walton BD',
                'business_logo'       => '/images/sellers/business/walton-logo.png',
                'business_email'      => 'business.walton@gmail.com',
                'business_address'    => 'Bashundhara R/A, Dhaka',
                'shipping_cost'       => 40,
                'trade_license_no'    => 'WA-TL-2023005',
                'trade_license_image' => '/images/sellers/licenses/walton-license.jpg',
                'shop_image'          => '/images/sellers/shops/walton-shop.jpg',
                'nid_no'              => '1954321987',
                'nid_front_image'     => '/images/sellers/nids/walton-nid-front.jpg',
                'nid_back_image'      => '/images/sellers/nids/walton-nid-back.jpg',
                'is_active'           => 1,
                'commission_type'     => CommissionType::PERCENTAGE->value,
                'commission_amount'   => 5.5,
            ],
            [
                'name'                => 'Test Distributor',
                'image'               => '/images/sellers/avatar/provider-logo-5.png',
                'email'               => 'test.seller@gmail.com',
                'phone'               => '01720000015',
                'password'            => Hash::make('password'),
                'business_name'       => 'Test Shop',
                'business_logo'       => '/images/sellers/business/walton-logo.png',
                'business_email'      => 'business.test@gmail.com',
                'business_address'    => 'Bashundhara R/A, Dhaka',
                'shipping_cost'       => 40,
                'trade_license_no'    => 'WA-TL-2023005',
                'trade_license_image' => '/images/sellers/licenses/walton-license.jpg',
                'shop_image'          => '/images/sellers/shops/walton-shop.jpg',
                'nid_no'              => '1954321957',
                'nid_front_image'     => '/images/sellers/nids/walton-nid-front.jpg',
                'nid_back_image'      => '/images/sellers/nids/walton-nid-back.jpg',
                'is_active'           => 0,
                'commission_type'     => CommissionType::FLAT->value,
                'commission_amount'   => 10,
            ],
        ];

        foreach ($sellers as $seller) {
            $seller['username'] = str_slug('sellers', 'username', $seller['name']);
            Seller::create($seller);
        }
    }
}
