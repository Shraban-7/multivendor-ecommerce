<?php

namespace Database\Factories;

use App\Domain\Product\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->word;

        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name.'-'.$this->faker->unique()->numberBetween(1000, 9999)),
            'image' => $this->faker->imageUrl(200, 200, 'category', true),
            'category_id' => rand(0, 1) ? Category::inRandomOrder()->first()?->id : null,
        ];
    }
}
