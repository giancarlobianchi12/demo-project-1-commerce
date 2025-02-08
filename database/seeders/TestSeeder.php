<?php

namespace Database\Seeders;

use App\Enums\ZoneTypeEnum;
use App\Models\ClientShipmentPrice;
use App\Models\Order;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Order::factory(20)->create();
        // User::factory(20)->isClient()->create();
        // User::factory(20)->isDriver()->create();

        $driver1 = User::factory()->isClient()->create();
        $clientShipmentPrice = ClientShipmentPrice::factory()->create([
            'short_price' => 1000,
            'medium_price' => 2000,
            'long_price' => 3000,
        ]);
        Order::factory(5)->create([
            'client_user_id' => $clientShipmentPrice->client_user_id,
            'driver_user_id' => $driver1->id,
            'zone_id' => Zone::where('name', ZoneTypeEnum::SHORT)->first()->id,
        ]);

        // PAYMENTS FOR DRIVER 2
        $driver2 = User::factory()->isClient()->create();
        $clientShipmentPrice2 = ClientShipmentPrice::factory()->create([
            'short_price' => 2000,
            'medium_price' => 3000,
            'long_price' => 4000,
        ]);
        Order::factory(5)->create([
            'client_user_id' => $clientShipmentPrice2->client_user_id,
            'driver_user_id' => $driver2->id,
            'zone_id' => Zone::where('name', ZoneTypeEnum::LONG)->first()->id,
        ]);

    }
}
