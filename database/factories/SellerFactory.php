<?php

namespace Database\Factories;

use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Seller>
 */
class SellerFactory extends Factory
{
    protected $model = Seller::class;

    public function definition(): array
    {
        $name = $this->faker->company();
        $username = Str::slug($name).'-'.Str::random(4);

        return [
            'name' => $this->faker->name(),
            'username' => $username,
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'password' => Hash::make('password'),
            'business_name' => $name,
            'business_email' => $this->faker->unique()->safeEmail(),
            'business_address' => $this->faker->address(),
            'nid_no' => $this->faker->numerify('##########'),
            'status' => Seller::ACTIVE,
            'is_active' => 1,
            'code' => strtoupper(Str::random(4)),
            'remember_token' => Str::random(10),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Seller::PENDING,
            'is_active' => 0,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Seller::ACTIVE,
            'is_active' => 1,
        ]);
    }

    public function blocked(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Seller::BLOCKED,
            'is_active' => 0,
        ]);
    }
}
