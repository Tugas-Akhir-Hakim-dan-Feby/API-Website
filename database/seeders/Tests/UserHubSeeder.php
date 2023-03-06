<?php

namespace Database\Seeders\Tests;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserHubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminHub = User::factory()->create([
            'name' => 'Admin Pusat Coba',
            'email' => 'admin.pusat.coba@mailinator.com',
            'role_id' => Role::ADMIN_PUSAT,
        ]);

        $adminHub->adminHub()->create([
            'position' => 'CEO',
            'phone' => '12121212',
            'address' => 'Indramayu',
            'status' => 1
        ]);
    }
}
