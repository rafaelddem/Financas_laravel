<?php

namespace Database\Factories;

use App\Enums\Relevance;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
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
            'relevance' => $this->faker->randomElement(Relevance::values()),
            'active' => true,
            'description' => $this->faker->text(255),
        ];
    }
}
