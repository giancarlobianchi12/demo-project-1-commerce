<?php

namespace Tests\Feature;

use App\Enums\OrderStatusEnum;
use App\Enums\ZoneTypeEnum;
use App\Models\Order;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Tests\Traits\MercadolibreBodyTraitTest;

class MercadolibreControllerTest extends TestCase
{
    use MercadolibreBodyTraitTest, RefreshDatabase;

    /**
     * Test hook transfer
     *
     * @return void
     */
    public function test_mercadolibre_hook_transfer()
    {
        $client = User::factory()->create([
            'external_id' => '1652273320',
            'external_access_token' => fake()->word(),
            'external_refresh_token' => fake()->word(),
            'type' => 'client',
        ]);

        $order = Order::factory()->create([
            'external_shipment_id' => '43052469151',
            'zone_id' => Zone::where('name', ZoneTypeEnum::SHORT)->first()->id,
            'client_user_id' => $client->id,
        ]);

        $userDriver = User::factory()->create([
            'external_id' => '123456789',
            'type' => 'driver',
        ]);

        $resource = '/flex/sites/MLA/shipments/43052469151/assignment/v1';
        $transferBody = [
            '_id' => '4d4afe9d-72aa-4260-81aa-afb1cf33af56',
            'topic' => 'flex-handshakes',
            'resource' => $resource,
            'user_id' => 1652273320,
            'application_id' => 3885598677369409,
            'sent' => '2024-01-29T03:57:35.321Z',
            'attempts' => 1,
            'received' => '2024-01-29T03:57:35.271Z',
            'actions' => [
                'transfer',
            ],
        ];

        Http::fake([
            env('MERCADOLIBRE_API_URI')."$resource" => Http::response(['driver_id' => '123456789']),
        ]);

        $response = $this->postJson(route('mercadolibre.hook'), $transferBody);

        $order->refresh();

        $response->assertSuccessful();
        $this->assertEquals($order->driver_user_id, $userDriver->id);
    }

    /**
     * Test hook shipment
     *
     * @return void
     */
    public function test_mercadolibre_hook_status_shipped()
    {
        $userClient = User::factory()->create([
            'external_id' => '1652273320',
            'external_access_token' => fake()->word(),
            'external_refresh_token' => fake()->word(),
            'type' => 'client',
        ]);

        Order::factory()->create([
            'external_shipment_id' => '43052469151',
            'zone_id' => Zone::where('name', ZoneTypeEnum::SHORT)->first()->id,
            'client_user_id' => $userClient->id,
            'status' => 'ready_to_ship',
        ]);

        $body = $this->shipmentBodyRequest;
        $shipmentResponse = $this->shipmentResponse;
        $shipmentResponse['status'] = OrderStatusEnum::SHIPPED;
        $costResponse = $this->costResponse;

        $resource = 'shipments/43052469151';

        Http::fake([
            env('MERCADOLIBRE_API_URI')."/$resource" => Http::response($shipmentResponse),
            env('MERCADOLIBRE_API_URI')."/$resource/costs" => Http::response($costResponse),
            env('MERCADOLIBRE_API_URI').'/flex/sites/MLA/shipments/43052469151/assignment/v1' => Http::response(['driver_id' => '123456789']),
        ]);

        $response = $this->postJson(route('mercadolibre.hook'), $body);

        $response->assertSuccessful();
        $this->assertDatabaseHas(Order::class, [
            'status' => OrderStatusEnum::SHIPPED,
        ]);
    }

    /**
     * Test hook shipment
     *
     * @return void
     */
    public function test_mercadolibre_hook_status_delivered()
    {
        $userClient = User::factory()->create([
            'external_id' => '1652273320',
            'external_access_token' => fake()->word(),
            'external_refresh_token' => fake()->word(),
            'type' => 'client',
        ]);

        $userDriver = User::factory()->create([
            'external_id' => '123456789',
            'type' => 'driver',
        ]);

        Order::factory()->create([
            'external_shipment_id' => '43052469151',
            'zone_id' => Zone::where('name', ZoneTypeEnum::SHORT)->first()->id,
            'client_user_id' => $userClient->id,
            'status' => OrderStatusEnum::SHIPPED,
        ]);

        $body = $this->shipmentBodyRequest;
        $shipmentResponse = $this->shipmentResponse;
        $shipmentResponse['status'] = OrderStatusEnum::DELIVERED;
        $costResponse = $this->costResponse;

        $resource = 'shipments/43052469151';

        Http::fake([
            env('MERCADOLIBRE_API_URI')."/$resource" => Http::response($shipmentResponse),
            env('MERCADOLIBRE_API_URI')."/$resource/costs" => Http::response($costResponse),
            env('MERCADOLIBRE_API_URI').'/flex/sites/MLA/shipments/43052469151/assignment/v1' => Http::response(['driver_id' => '123456789']),
        ]);

        $response = $this->postJson(route('mercadolibre.hook'), $body);

        $response->assertSuccessful();
        $this->assertDatabaseHas(Order::class, [
            'status' => OrderStatusEnum::DELIVERED,
        ]);
    }

    public function test_mercadolibre_hook_shipment_status_cancelled()
    {
        $userClient = User::factory()->create([
            'external_id' => '1652273320',
            'external_access_token' => fake()->word(),
            'external_refresh_token' => fake()->word(),
        ]);

        $userDriver = User::factory()->create([
            'external_id' => '123456789',
        ]);

        $resource = 'orders/2000007546497076';

        $orderRequest = [
            '_id' => '0d424afb-b481-4786-bcf6-4846cf02e589',
            'topic' => 'orders_v2',
            'resource' => '/orders/2000007546497076',
            'user_id' => 1652273320,
            'application_id' => 3885598677369409,
            'sent' => '2024-02-06T16:19:47.18Z',
            'attempts' => 1,
            'received' => '2024-02-06T16:19:47.05Z',
            'actions' => [
                'status',
            ],
        ];

        Http::fake([
            env('MERCADOLIBRE_API_URI')."/$resource" => Http::response($this->orderBodyResponse),
        ]);

        $order = Order::factory()->create([
            'status' => OrderStatusEnum::SHIPPED,
            'external_id' => '2000007546497076',
        ]);

        $response = $this->postJson(route('mercadolibre.hook'), $orderRequest);

        $response->assertSuccessful();
        $order->refresh();
        $this->assertEquals(OrderStatusEnum::CANCELLED, $order->status);
    }
}
