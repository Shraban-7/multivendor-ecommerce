<?php

namespace Database\Factories;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductStock;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductStock>
 */
class ProductStockFactory extends Factory
{
    protected $model = ProductStock::class;

    public function definition(): array
    {
        $quantity = $this->faker->numberBetween(1, 100);
        $buyingPrice = $this->faker->randomFloat(2, 5, 500);

        return [
            'quantity' => $quantity,
            'buying_price' => $buyingPrice,
            'sub_total' => $buyingPrice * $quantity,
        ];
    }

    public function forProduct(Product $product): static
    {
        return $this->state([
            'product_id' => $product->id,
            'seller_id' => $product->seller_id,
        ]);
    }
}
