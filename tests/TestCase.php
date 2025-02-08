<?php

namespace Tests;

use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function getUserAdmin(): User
    {
        $user = User::factory()->create([
            'type' => UserTypeEnum::ADMIN,
        ]);

        return $user;
    }

    public function getUserClient(): User
    {
        $user = User::factory()->create([
            'type' => UserTypeEnum::CLIENT,
        ]);

        return $user;
    }
}
