<?php

namespace Database\Factories;

use App\Enums\PaymentType;
use App\Models\Card;
use App\Models\Category;
use App\Models\PaymentMethod;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionBase>
 */
class TransactionBaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $paymentMethod = PaymentMethod::factory()->create();

        $name = $this->faker->name();
        $name = preg_replace('/[^A-Za-z0-9À-ÖØ-öø-ÿç.\-\(\) ]/', '', $name);
        $name = substr($name, 0, 30);

        $card = Card::factory()->fromWallet()->create();

        $data = [
            'title' => $name, 
            'category_id' => Category::factory()->create()->id, 
            'payment_method_id' => $paymentMethod->id, 
            'source_wallet_id' => $card->wallet_id, 
            'destination_wallet_id' => Wallet::factory()->create()->id, 
        ];

        if ($paymentMethod->type == PaymentType::Credit || $paymentMethod->type == PaymentType::Debit) {
            $data += [ 'card_id' => $card->id, ];
        }

        return $data;
    }

    public function credit()
    {
        return $this->state(function (array $attributes) {
            $card = Card::factory()->fromWallet()->create();

            return [
                'payment_method_id' => PaymentMethod::factory()->create([
                    'type' => PaymentType::Credit->value, 
                ]),
                'card_id' => $card->id,
                'source_wallet_id' => $card->wallet_id,
                'destination_wallet_id' => Wallet::factory()->fromOwner()->create()->id,
            ];
        });
    }
}
