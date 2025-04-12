<?php

namespace Database\Factories;

use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wallet>
 */
class WalletFactory extends Factory
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
            'owner_id' => 1,
            'main_wallet' => true,
            'active' => true,
            'description' => $this->faker->text(),
        ];
    }

    public function fromOwner(?Owner $owner = null, bool $first = false)
    {
        if (empty($owner)) {
            $owner = Owner::factory()->create();
        }

        return $this->state(function (array $attributes) use ($owner, $first) {
            return [
                'name' => $owner->name . ($first ? '' : ' - ' . $this->faker->ean8()),
                'owner_id' => $owner->id,
                'main_wallet' => $first,
                'active' => $owner->active,
            ];
        });
    }
}
