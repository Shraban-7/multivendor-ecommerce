<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    protected $model = User::class;

    public function definition(): array
    {
        static $password;

        return [
            'fullname' => $this->faker->name(),
            'username' => $this->faker->unique()->userName(),
            'display_name' => $this->faker->optional()->word(),
            'image' => $this->faker->optional()->imageUrl(200, 200, 'people'),

            'email' => $this->faker->unique()->safeEmail(),
            'secondary_email' => $this->faker->unique()->optional()->safeEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'password' => $password ??= Hash::make('password'),

            'country_id' => $this->faker->optional()->numberBetween(1, 250),
            'state_id' => $this->faker->optional()->numberBetween(1, 500),
            'zip' => $this->faker->optional()->postcode(),

            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
