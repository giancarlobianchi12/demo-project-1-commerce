<?php

namespace Tests\Feature\Mercadolibre;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Tests\Traits\MercadolibreBodyTraitTest;

class MercadolibreRefreshTokenCommandTest extends TestCase
{
    use MercadolibreBodyTraitTest, RefreshDatabase;

    /**
     * Test hook shipment
     *
     * @return void
     */
    public function test_command_can_refresh_mercadolibre_token()
    {
        $user = User::factory()->create([
            'external_id' => '1652273320',
            'external_access_token' => fake()->word(),
            'external_refresh_token' => fake()->word(),
            'type' => 'client',
            'external_expires_at' => now()->subDay(),
        ]);

        Order::factory()->create([
            'external_shipment_id' => '44462102666',
            'user_id' => $user->id,
            'status' => OrderStatusEnum::SHIPPED,
        ]);

        $resource = '/oauth/token';

        Http::fake([
            env('MERCADOLIBRE_API_URI')."$resource" => Http::response($this->refreshTokenResponse),
        ]);

        Artisan::call('mercadolibre:refresh-access-token');
        $user->refresh();
        $this->assertEquals($this->refreshTokenResponse['access_token'], $user->external_access_token);
    }
}
