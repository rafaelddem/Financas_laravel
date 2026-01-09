<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Installment>
 */
class InstallmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'installment_number' => 1,
            'installment_total' => 1,
            'installment_date' => Carbon::now(),
            'gross_value' => $this->faker->randomFloat(2, 20, 750), 
            'discount_value' => 0.00, 
            'interest_value' => 0.00, 
            'rounding_value' => 0.00, 
        ];
    }
}
