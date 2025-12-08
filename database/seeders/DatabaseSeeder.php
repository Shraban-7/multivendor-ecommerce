<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AdminSeeder::class,
            SellerSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductUnitSeeder::class,
            OptionSeeder::class,
            ProductSeeder::class,
            ProductStockSeeder::class,
            // OrderSeeder::class,
            // OrderItemSeeder::class,
            WishlistSeeder::class,
            // ReviewSeeder::class,
            CouponSeeder::class,
            CategoryBannerSeeder::class,
            HomeMidBannerSeeder::class,
            SocialLinkSeeder::class,
            PaymentOptionSeeder::class,
            // ProductVariantSeeder::class,
            // VariantOptionSeeder::class,
            SystemSettingSeeder::class,
            PermissionSeeder::class,
            PaymentGatewaySeeder::class,

            DivisionSeeder::class,
            DistrictSeeder::class,
            UpazilaSeeder::class,
            UnionSeeder::class,

            CategoryOptionSeeder::class,
        ]);
    }
}
