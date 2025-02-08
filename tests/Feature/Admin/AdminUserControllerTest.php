<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminUserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_get_user()
    {
        $user = $this->getUserAdmin();

        User::factory(5)->create();

        $response = $this->actingAs($user)->getJson(route('admin.users.show', User::first()->id));

        $response->assertSuccessful();
    }

    public function test_admin_update_user()
    {
        $admin = $this->getUserAdmin();

        $user = User::factory()->create();

        $response = $this->actingAs($admin)->putJson(route('admin.users.update', $user->id), [
            'name' => 'Some name',
        ]);

        $response->assertSuccessful();
        $user->refresh();
        $this->assertEquals('Some name', $user->name);
    }

    public static function userTypeDataProvider()
    {
        return [
            'admin' => ['admin'],
            'client' => ['client'],
        ];
    }

    #[DataProvider('userTypeDataProvider')]
    public function test_admin_create_user_with_different_types($type)
    {
        $user = $this->getUserAdmin();

        $userData = User::factory()->make([
            'type' => $type,
        ])->toArray();

        $response = $this->actingAs($user)->postJson(route('admin.users.store', $user->id), [
            ...$userData,
            'password' => 'password',
        ]);

        $response->assertCreated();
    }

    #[DataProvider('userTypeDataProvider')]
    public function test_admin_list_users($type)
    {
        $user = $this->getUserAdmin();

        User::factory(5)->create();

        $response = $this->actingAs($user)->getJson(route('admin.users.index', ['type' => $type]));

        $count = User::where('type', $type)->count();

        $response->assertSuccessful()
            ->assertJson(
                fn (AssertableJson $json) => $json->count('data', $count)
                    ->etc()
            );
    }

    public function test_no_admin_can_not_create_users($type)
    {
        $user = $this->getUserClient();

        $userData = User::factory()->make([
            'type' => 'client',
        ])->toArray();

        $response = $this->actingAs($user)->postJson(route('admin.users.store', $user->id), [
            ...$userData,
            'password' => 'password',
        ]);

        $response->assertUnauthorized();
    }

    public function test_no_admin_can_not_get_users()
    {
        $user = $this->getUserClient();

        User::factory(5)->create();

        $response = $this->actingAs($user)->getJson(route('admin.users.show', User::first()->id));

        $response->assertUnauthorized();
    }
}
