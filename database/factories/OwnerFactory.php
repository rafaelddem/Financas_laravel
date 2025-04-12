<?php

namespace Database\Factories;

use App\Models\Owner;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Owner>
 */
class OwnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => substr($this->faker->name(), 0, 30),
            'active' => true,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Owner $owner) {
            Wallet::factory()->fromOwner($owner, true)->create();
        });
    }

    public function withMoreWallets(int $quantity = 3): Factory
    {
        return $this->afterCreating(function (Owner $owner) use ($quantity) {
            Wallet::factory()->fromOwner($owner)->count($quantity)->create();
        });
    }
}
