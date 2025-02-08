<?php

namespace Database\Factories;

use App\Enums\OrderStatusEnum;
use App\Enums\ZoneTypeEnum;
use App\Models\Receiver;
use App\Models\User;
use App\Models\Zone;
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
            'receiver_id' => Receiver::factory(),
            'external_shipment_id' => fake()->randomNumber(9),
            'zone_id' => Zone::where('name', ZoneTypeEnum::getRandomValue())->first()->id,
            'driver_user_id' => User::factory()->isDriver(),
            'client_user_id' => User::factory()->isClient(),
        ];
    }
}
