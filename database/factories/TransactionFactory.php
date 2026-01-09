<?php

namespace Database\Factories;

use App\Enums\PaymentType;
use App\Models\Card;
use App\Models\Category;
use App\Models\Installment;
use App\Models\Owner;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = Category::factory()->create();

        $title = $this->faker->name();
        $title = preg_replace('/[^A-Za-z0-9À-ÖØ-öø-ÿç.\-\(\) ]/', '', $title);
        $title = substr($title, 0, 50);

        return [
            'title' => $title,
            'transaction_date' => Carbon::now(),
            'processing_date' => Carbon::now(),
            'category_id' => $category->id,
            'relevance' => $category->relevance,
            'payment_method_id' => PaymentMethod::factory()->create(),
            'source_wallet_id' => 1,
            'destination_wallet_id' => 2,
            'gross_value' => $this->faker->randomFloat(2, 20, 750), 
            'discount_value' => 0.00, 
            'interest_value' => 0.00, 
            'rounding_value' => 0.00, 
        ];
    }

    public function wallets(?Wallet $sourceWallet = null, ?Wallet $destinationWallet = null)
    {
        if (empty($sourceWallet)) 
            $sourceWallet = Wallet::factory()->fromOwner()->create();

        if (empty($destinationWallet)) 
            $destinationWallet = Wallet::factory()->fromOwner()->create();

        return $this->state(function (array $attributes) use ($sourceWallet, $destinationWallet) {
            return [
                'payment_method_id' => PaymentMethod::factory()->simple()->create(),
                'source_wallet_id' => $sourceWallet->id,
                'destination_wallet_id' => $destinationWallet->id,
            ];
        });
    }

    public function simple()
    {
        return $this->state(function (array $attributes) {
            return [
                'payment_method_id' => PaymentMethod::factory()->create([
                    'type' => $this->faker->randomElement([PaymentType::Notes->value, PaymentType::Transfer->value]), 
                ]),
                'source_wallet_id' => Wallet::factory()->fromOwner()->create()->id,
                'destination_wallet_id' => Wallet::factory()->fromOwner()->create()->id,
            ];
        });
    }

    public function debit(?Card $card = null)
    {
        if (empty($card)) 
            $card = Card::factory()->create();

        return $this->state(function (array $attributes) use ($card) {
            return [
                'payment_method_id' => PaymentMethod::factory()->create([
                    'type' => PaymentType::Debit->value, 
                ]),
                'card_id' => $card->id,
                'source_wallet_id' => $card->wallet_id,
                'destination_wallet_id' => Wallet::factory()->fromOwner()->create()->id,
            ];
        });
    }

    public function credit(?Card $card = null, bool $withInstallments = true)
    {
        if (empty($card)) 
            $card = Card::factory()->create();

        return $this->state(function (array $attributes) use ($card, $withInstallments) {
            $baseData = [
                'payment_method_id' => PaymentMethod::factory()->create([
                    'type' => PaymentType::Credit->value, 
                ]),
                'card_id' => $card->id,
                'source_wallet_id' => $card->wallet_id,
                'destination_wallet_id' => Wallet::factory()->fromOwner()->create()->id,
            ];

            if ($withInstallments) {
                $baseData += [
                    'installments' => [
                        [
                            'installment_number' => 1,
                            'installment_total' => 1,
                            'installment_date' => Carbon::now()->format('Y-m-d'), 
                            'gross_value' => $attributes['gross_value'], 
                            'discount_value' => 0.00, 
                            'interest_value' => 0.00, 
                            'rounding_value' => 0.00, 
                        ]
                    ]
                ];
            }

            return $baseData;
        });
    }

    public function withInstallments(int $installment_total = 1)
    {
        return $this->afterCreating(function (Transaction $transaction) use ($installment_total) {
            if ($transaction->paymentMethod->type == PaymentType::Credit) {
                for ($installment_number = 1; $installment_number <= $installment_total; $installment_number++) { 
                    Installment::factory()->create([
                        'transaction_id' => $transaction->id, 
                        'installment_number' => $installment_number,
                        'installment_total' => $installment_total,
                        'installment_date' => $transaction->processing_date->addMonths($installment_number - 1), 
                        'gross_value' => $transaction->gross_value, 
                    ]);
                }
            }
        });
    }
}
