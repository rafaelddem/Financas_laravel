<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'card_id' => 1, 
            'start_date' => Carbon::now(), 
            'end_date' => Carbon::now()->addMonth()->subDay(), 
            'due_date' => Carbon::now()->addMonth()->addDays(10), 
            'value' => $this->faker->randomFloat(2, 200, 350), 
            'status' => InvoiceStatus::Open->value,
        ];
    }

    public function fromCard(?Card $card = null)
    {
        if (empty($card)) {
            $card = Card::factory()->fromWallet()->create();
        }

        return $this->state(function (array $attributes) use ($card) {
            return [
                'card_id' => $card->id,
            ];
        });
    }
}
