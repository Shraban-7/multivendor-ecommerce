<?php

namespace Database\Seeders;

use App\Models\Seller;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SellerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sellers = [
            [
                'fullname'             => 'Unilever Seller',
                'image'                => '/images/sellers/avatar/provider-logo-1.png',
                'email'                => 'unilever.seller@gmail.com',
                'phone'                => '01720000001',
                'password'             => Hash::make('password'),
                'business_name'        => 'Unilever Bangladesh Ltd',
                'business_logo'        => '/images/sellers/business/unilever-logo.png',
                'business_email'       => 'business.unilever@gmail.com',
                'business_address'     => 'Tejgaon I/A, Dhaka',
                'country_id'           => 1,
                'zip'                  => '1215',
                'shipping_cost'        => 25,
                'trade_license_no'     => 'UL-TL-2023001',
                'trade_license_image'  => '/images/sellers/licenses/unilever-license.jpg',
                'shop_image'           => '/images/sellers/shops/unilever-shop.jpg',
                'nid_no'               => '1976543210',
                'nid_front_image'      => '/images/sellers/nids/unilever-nid-front.jpg',
                'nid_back_image'       => '/images/sellers/nids/unilever-nid-back.jpg',
            ],
            [
                'fullname'             => 'Nestlé Partner',
                'image'                => '/images/sellers/avatar/provider-logo-2.png',
                'email'                => 'nestle.seller@gmail.com',
                'phone'                => '01720000002',
                'password'             => Hash::make('password'),
                'business_name'        => 'Nestlé Bangladesh',
                'business_logo'        => '/images/sellers/business/nestle-logo.png',
                'business_email'       => 'business.nestle@gmail.com',
                'business_address'     => 'Kawran Bazar, Dhaka',
                'country_id'           => 1,
                'zip'                  => '1207',
                'shipping_cost'        => 20,
                'trade_license_no'     => 'NE-TL-2023002',
                'trade_license_image'  => '/images/sellers/licenses/nestle-license.jpg',
                'shop_image'           => '/images/sellers/shops/nestle-shop.jpg',
                'nid_no'               => '1987654321',
                'nid_front_image'      => '/images/sellers/nids/nestle-nid-front.jpg',
                'nid_back_image'       => '/images/sellers/nids/nestle-nid-back.jpg',
            ],
            [
                'fullname'             => 'Marico Agent',
                'image'                => '/images/sellers/avatar/provider-logo-3.png',
                'email'                => 'marico.seller@gmail.com',
                'phone'                => '01720000003',
                'password'             => Hash::make('password'),
                'business_name'        => 'Marico Ltd',
                'business_logo'        => '/images/sellers/business/marico-logo.png',
                'business_email'       => 'business.marico@gmail.com',
                'business_address'     => 'Gulshan, Dhaka',
                'country_id'           => 1,
                'zip'                  => '1212',
                'shipping_cost'        => 15,
                'trade_license_no'     => 'MA-TL-2023003',
                'trade_license_image'  => '/images/sellers/licenses/marico-license.jpg',
                'shop_image'           => '/images/sellers/shops/marico-shop.jpg',
                'nid_no'               => '1998765432',
                'nid_front_image'      => '/images/sellers/nids/marico-nid-front.jpg',
                'nid_back_image'       => '/images/sellers/nids/marico-nid-back.jpg',
            ],
            [
                'fullname'             => 'Aarong Vendor',
                'image'                => '/images/sellers/avatar/provider-logo-4.png',
                'email'                => 'aarong.seller@gmail.com',
                'phone'                => '01720000004',
                'password'             => Hash::make('password'),
                'business_name'        => 'Aarong',
                'business_logo'        => '/images/sellers/business/aarong-logo.png',
                'business_email'       => 'business.aarong@gmail.com',
                'business_address'     => 'Uttara, Dhaka',
                'country_id'           => 1,
                'zip'                  => '1230',
                'shipping_cost'        => 35,
                'trade_license_no'     => 'AR-TL-2023004',
                'trade_license_image'  => '/images/sellers/licenses/aarong-license.jpg',
                'shop_image'           => '/images/sellers/shops/aarong-shop.jpg',
                'nid_no'               => '1965432189',
                'nid_front_image'      => '/images/sellers/nids/aarong-nid-front.jpg',
                'nid_back_image'       => '/images/sellers/nids/aarong-nid-back.jpg',
            ],
            [
                'fullname'             => 'Walton Distributor',
                'image'                => '/images/sellers/avatar/provider-logo-5.png',
                'email'                => 'walton.seller@gmail.com',
                'phone'                => '01720000005',
                'password'             => Hash::make('password'),
                'business_name'        => 'Walton BD',
                'business_logo'        => '/images/sellers/business/walton-logo.png',
                'business_email'       => 'business.walton@gmail.com',
                'business_address'     => 'Bashundhara R/A, Dhaka',
                'country_id'           => 1,
                'zip'                  => '1229',
                'shipping_cost'        => 40,
                'trade_license_no'     => 'WA-TL-2023005',
                'trade_license_image'  => '/images/sellers/licenses/walton-license.jpg',
                'shop_image'           => '/images/sellers/shops/walton-shop.jpg',
                'nid_no'               => '1954321987',
                'nid_front_image'      => '/images/sellers/nids/walton-nid-front.jpg',
                'nid_back_image'       => '/images/sellers/nids/walton-nid-back.jpg',
            ],
        ];

        foreach ($sellers as $seller) {
            $seller['username'] = str_slug('sellers', 'username', $seller['fullname']);
            Seller::create($seller);
        }
    }
}
