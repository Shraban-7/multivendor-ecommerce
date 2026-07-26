<?php

namespace Database\Factories;

use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $sellingPrice = $this->faker->randomFloat(2, 10, 1000);
        $buyingPrice = $this->faker->randomFloat(2, 5, $sellingPrice);

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name).'-'.$this->faker->unique()->numerify('####'),
            'sku' => strtoupper($this->faker->unique()->bothify('??####')),
            'short_description' => $this->faker->sentence(),
            'description' => $this->faker->paragraphs(2, true),
            'cost_price' => $buyingPrice,
            'price' => $sellingPrice,
            'stock_in' => $this->faker->numberBetween(0, 200),
            'stock_out' => 0,
            'low_stock_quantity' => 5,
            'status' => Product::STATUS_ACTIVE,
            'payment_type' => PaymentType::FULL_PAYMENT,
            'unit_value' => '1',
        ];
    }

    public function active(): static
    {
        return $this->state(['status' => Product::STATUS_ACTIVE]);
    }

    public function inactive(): static
    {
        return $this->state(['status' => Product::STATUS_INACTIVE]);
    }

    public function pending(): static
    {
        return $this->state(['status' => Product::STATUS_PENDING_APPROVAL]);
    }

    public function withSeller(?int $sellerId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'seller_id' => $sellerId ?? Seller::inRandomOrder()->first()?->id,
        ]);
    }

    public function withCategory(?int $categoryId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'category_id' => $categoryId ?? Category::inRandomOrder()->first()?->id,
        ]);
    }

    public function withBrand(?int $brandId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'brand_id' => $brandId ?? Brand::inRandomOrder()->first()?->id,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state([
            'stock_in' => 0,
            'stock_out' => 0,
        ]);
    }

    public function lowStock(int $quantity = 2): static
    {
        return $this->state([
            'stock_in' => $quantity,
            'stock_out' => 0,
            'low_stock_quantity' => 5,
        ]);
    }

    public function withComparePrice(?float $comparePrice = null): static
    {
        return $this->state(function (array $attributes) use ($comparePrice) {
            $price = $attributes['price'];
            $sale = $comparePrice ?? round($price * 0.9, 2);

            return [
                'compare_price' => max(0, min($sale, $price - 0.01)),
            ];
        });
    }
}
