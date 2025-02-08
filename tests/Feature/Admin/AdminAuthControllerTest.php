<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_auth()
    {
        $user = $this->getUserAdmin();

        $response = $this->postJson(route('auth.login', [
            'email' => $user->email,
            'password' => 'password',
        ]));

        $response->assertSuccessful();
    }

    public function test_admin_can_get_me()
    {
        $user = $this->getUserAdmin();

        $response = $this->actingAs($user)->getJson(route('auth.me'));

        $response->assertSuccessful();
    }

    public function test_admin_can_logout()
    {
        $user = $this->getUserAdmin();

        $response = $this->actingAs($user)->postJson(route('auth.logout'));

        $response->assertNoContent();
    }
}
