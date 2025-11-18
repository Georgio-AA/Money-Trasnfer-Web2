<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'age' => fake()->numberBetween(18, 65),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->e164PhoneNumber(),
            'balance' => fake()->randomFloat(2, 1000, 50000), // Random balance between 1000-50000
            'currency' => fake()->randomElement(['USD', 'EUR', 'GBP', 'CAD']),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'verification_token' => null,
            'verification_due' => null,
            'is_verified' => true,
            'role' => 'user',
            'remember_token' => Str::random(10),
            'is_admin' => false,
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
