<?php

namespace Tests\Feature\Client;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_auth()
    {
        $user = $this->getUserClient();

        $response = $this->postJson(route('auth.login', [
            'email' => $user->email,
            'password' => 'password',
        ]));

        $response->assertSuccessful();
    }

    public function test_client_can_get_me()
    {
        $user = $this->getUserClient();

        $response = $this->actingAs($user)->getJson(route('auth.me'));

        $response->assertSuccessful();
    }

    public function test_client_can_logout()
    {
        $user = $this->getUserClient();

        $response = $this->actingAs($user)->postJson(route('auth.logout'));

        $response->assertNoContent();
    }
}
