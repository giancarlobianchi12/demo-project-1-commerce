<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'external_id' => fake()->randomNumber(9),
            'title' => fake()->title(),
            'status' => OrderStatusEnum::getRandomValue(),
            'price' => fake()->numberBetween(1, 99999).'.01',
            'external_shipment_id' => fake()->randomNumber(9),
            'user_id' => User::factory()->isDriver(),
        ];
    }
}
