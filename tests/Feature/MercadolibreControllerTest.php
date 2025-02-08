<?php

namespace Tests\Feature;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Tests\Traits\MercadolibreBodyTraitTest;

class MercadolibreControllerTest extends TestCase
{
    use MercadolibreBodyTraitTest, RefreshDatabase;

    /**
     * Test hook shipment
     *
     * @return void
     */
    public function test_hook_mercadolibre_status_delivered()
    {
        User::factory()->create([
            'external_id' => '1652273320',
            'external_access_token' => fake()->word(),
            'external_refresh_token' => fake()->word(),
            'type' => 'client',
        ]);

        $client = User::factory()->create([
            'external_id' => '123456789',
            'type' => 'client',
        ]);

        Order::factory()->create([
            'external_shipment_id' => '44462102666',
            'user_id' => $client->id,
            'status' => OrderStatusEnum::SHIPPED,
        ]);

        $resource = '/shipments/44462102666';

        Http::fake([
            env('MERCADOLIBRE_API_URI')."/$resource" => Http::response($this->shipmentResponse),
        ]);

        $response = $this->postJson(route('mercadolibre.hook'), $this->shipmentBodyRequest);

        $response->assertSuccessful();
        $this->assertDatabaseHas(Order::class, [
            'status' => OrderStatusEnum::DELIVERED,
        ]);
    }
}
