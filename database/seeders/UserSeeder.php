<?php

namespace Database\Seeders;

use App\Enums\UserTypeEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin
        User::factory()->create([
            'name' => 'Admin user',
            'email' => 'admin@admin.com',
            'type' => UserTypeEnum::ADMIN,
        ]);
    }
}
