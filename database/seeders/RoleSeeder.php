<?php

namespace Database\Seeders;

use App\Models\Role as ModelsRole;
use App\Models\User;
use Database\Seeders\Traits\DisableForeignKey;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    use DisableForeignKey, TruncateTable;

    public function run()
    {
        $roles = ['Admin App', 'Admin Pusat', 'Admin Cabang', 'Pakar', 'Member Company', 'Member Welder', 'Guest'];

        $this->disableForeignKeys();
        $this->truncate('roles');
        $this->truncate('permissions');

        foreach ($roles as $role) {
            $role = Role::create([
                'name' => $role,
                'guard_name' => 'api'
            ]);
        }

        $users = User::all();
        foreach ($users as $user) {
            if ($user->role_id == ModelsRole::ADMIN_APP) {
                $user->assignRole(Role::where('id', ModelsRole::ADMIN_APP)->first());
            }

            if ($user->role_id == ModelsRole::ADMIN_PUSAT) {
                $user->assignRole(Role::where('id', ModelsRole::ADMIN_PUSAT)->first());
            }

            if ($user->role_id == ModelsRole::ADMIN_CABANG) {
                $user->assignRole(Role::where('id', ModelsRole::ADMIN_CABANG)->first());
            }

            if ($user->role_id == ModelsRole::PAKAR) {
                $user->assignRole(Role::where('id', ModelsRole::PAKAR)->first());
            }

            if ($user->role_id == ModelsRole::MEMBER_COMPANY) {
                $user->assignRole(Role::where('id', ModelsRole::MEMBER_COMPANY)->first());
            }

            if ($user->role_id == ModelsRole::MEMBER_WELDER) {
                $user->assignRole(Role::where('id', ModelsRole::MEMBER_WELDER)->first());
            }

            if ($user->role_id == ModelsRole::GUEST) {
                $user->assignRole(Role::where('id', ModelsRole::GUEST)->first());
            }
        }
    }
}
