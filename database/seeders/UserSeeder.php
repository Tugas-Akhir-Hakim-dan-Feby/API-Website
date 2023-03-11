<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        User::factory()->create([
            'email' => 'admin.app@mailinator.com',
            'role_id' => Role::ADMIN_APP,
        ]);

        User::factory()->create([
            'email' => 'admin.pusat@mailinator.com',
            'role_id' => Role::ADMIN_HUB,
        ]);

        User::factory()->create([
            'email' => 'admin.cabang@mailinator.com',
            'role_id' => Role::ADMIN_BRANCH,
        ]);

        User::factory()->create([
            'email' => 'pakar@mailinator.com',
            'role_id' => Role::EXPERT,
        ]);

        User::factory()->create([
            'email' => 'member.company@mailinator.com',
            'role_id' => Role::MEMBER_COMPANY,
        ]);

        User::factory()->create([
            'email' => 'welder.company@mailinator.com',
            'role_id' => Role::MEMBER_WELDER,
        ]);

        User::factory()->create([
            'email' => 'guest@mailinator.com',
            'role_id' => Role::GUEST,
        ]);
    }
}
