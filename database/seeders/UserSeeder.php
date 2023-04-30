<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Str;
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

        $adminHub = User::factory()->create([
            'email' => 'admin.pusat@mailinator.com',
            'role_id' => Role::ADMIN_PUSAT,
        ]);

        $adminHub->adminHub()->create([
            'uuid' => Str::uuid(),
            'position' => 'CEO',
            'phone' => '12121212',
            'address' => 'Indramayu',
            'status' => 1
        ]);

        $branch = Branch::create([
            'uuid' => Str::uuid(),
            'branch_name' => "Chung Jui Fang",
            'branch_address' => "Jawa Tengah",
            'branch_phone' => "12121221",
        ]);

        $userBranch = User::factory()->create([
            'email' => 'admin.cabang@mailinator.com',
            'role_id' => Role::ADMIN_CABANG,
        ]);

        $userBranch->adminBranch()->create([
            'uuid' => Str::uuid(),
            'nip' => '111',
            'position' => 'CEO',
            'phone' => '12121212',
            'address' => 'Indramayu',
            'status' => 1,
            'date_birth' => Carbon::now(),
            'birth_place' => 'Indramayu',
            'branch_id' => $branch->id
        ]);

        User::factory()->create([
            'email' => 'pakar@mailinator.com',
            'role_id' => Role::PAKAR,
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
