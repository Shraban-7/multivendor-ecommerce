<?php

namespace Database\Factories;

use App\Domain\Product\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        $name = $this->faker->company;

        return [
            'name' => $name,
            'slug' => Str::slug($name.'-'.$this->faker->unique()->numberBetween(1000, 9999)),
            'image' => $this->faker->imageUrl(200, 200, 'brand', true),
        ];
    }
}
