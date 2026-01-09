<?php

namespace Database\Factories;

use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PaymentMethod>
 */
class PaymentMethodFactory extends Factory
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
            'type' => $this->faker->randomElement(PaymentType::values()),
            'active' => true,
        ];
    }

    public function simple()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => $this->faker->randomElement([PaymentType::Notes->value, PaymentType::Transfer->value]),
            ];
        });
    }
}
