<?php

namespace Database\Factories;

use App\Enums\Relevance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TransactionType>
 */
class TransactionTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => substr($this->faker->name(), 0, 45),
            'relevance' => $this->faker->randomElement(Relevance::names()),
            'active' => true,
        ];
    }
}
