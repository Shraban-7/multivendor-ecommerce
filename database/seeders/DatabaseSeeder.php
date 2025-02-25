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
            CountrySeeder::class,
            SellerSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            CategoryProductSeeder::class,
            ProductImageSeeder::class,
            ProductAttributeSeeder::class,
            // ProductVariantSeeder::class,
            ProductStockSeeder::class,
            ProductUnitSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            WishlistSeeder::class,
            ReviewSeeder::class,
            CouponSeeder::class,
            CategoryBannerSeeder::class
        ]);

    }
}
