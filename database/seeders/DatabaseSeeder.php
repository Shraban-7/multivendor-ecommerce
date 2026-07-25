<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Domain\Order\Database\Seeders\CouponSeeder;
use App\Domain\Order\Database\Seeders\WishlistSeeder;
use App\Domain\Product\Database\Seeders\BrandSeeder;
use App\Domain\Product\Database\Seeders\CategoryBannerSeeder;
use App\Domain\Product\Database\Seeders\CategoryOptionSeeder;
use App\Domain\Product\Database\Seeders\CategorySeeder;
use App\Domain\Product\Database\Seeders\HomeMidBannerSeeder;
use App\Domain\Product\Database\Seeders\OptionSeeder;
use App\Domain\Product\Database\Seeders\ProductSeeder;
use App\Domain\Product\Database\Seeders\ProductStockSeeder;
use App\Domain\Product\Database\Seeders\ProductUnitSeeder;
use App\Domain\Shipping\Database\Seeders\DistrictSeeder;
use App\Domain\Shipping\Database\Seeders\DivisionSeeder;
use App\Domain\Shipping\Database\Seeders\UnionSeeder;
use App\Domain\Shipping\Database\Seeders\UpazilaSeeder;
use App\Domain\Vendor\Database\Seeders\SellerSeeder;
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
            // \App\Domain\Order\Database\Seeders\OrderSeeder::class,
            // \App\Domain\Order\Database\Seeders\OrderItemSeeder::class,
            WishlistSeeder::class,
            // \App\Domain\Review\Database\Seeders\ReviewSeeder::class,
            CouponSeeder::class,
            CategoryBannerSeeder::class,
            HomeMidBannerSeeder::class,
            SocialLinkSeeder::class,
            PaymentOptionSeeder::class,
            // \App\Domain\Product\Database\Seeders\ProductVariantSeeder::class,
            // \App\Domain\Product\Database\Seeders\VariantOptionSeeder::class,
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
