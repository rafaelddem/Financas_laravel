<?php

namespace Database\Factories;

use App\Models\Card;
use App\Models\Invoice;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wallet_id' => 1,
            'name' => substr($this->faker->name(), 0, 20),
            'card_type' => $this->faker->randomElement(['debit', 'credit']),
            'first_day_month' => $this->faker->randomDigitNotZero(),
            'days_to_expiration' => $this->faker->randomDigitNotZero(),
            'active' => true,
        ];
    }

    public function fromWallet(?Wallet $wallet = null)
    {
        if (empty($wallet)) {
            $wallet = Wallet::factory()->fromOwner()->create();
        }

        return $this->state(function (array $attributes) use ($wallet) {
            return [
                'wallet_id' => $wallet->id,
            ];
        });
    }

    public function configure()
    {
        return $this->afterCreating(function (Card $card) {
            Invoice::factory()->create([
                'card_id' => $card->id,
            ]);
        });
    }
}
