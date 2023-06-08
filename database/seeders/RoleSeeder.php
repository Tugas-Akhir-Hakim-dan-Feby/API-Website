<?php

namespace Database\Seeders;

use App\Models\Role as ModelsRole;
use App\Models\User;
use Database\Seeders\Traits\DisableForeignKey;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    use DisableForeignKey, TruncateTable;

    public function run()
    {
        $roles = ['Admin App', 'Admin Pusat', 'Admin Cabang', 'Pakar', 'Member Company', 'Member Welder', 'Guest'];

        $access[1]['Dashboard'] = ['index'];
        $access[1]['Branch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination'];
        $access[1]['AdminHub'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[1]['AdminBranch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[1]['Expert'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel', 'confirmation'];
        $access[1]['CompanyMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $access[1]['WelderMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $access[1]['WelderSkill'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[1]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[1]['ExamPacket'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[1]['Exam'] = ['index', 'create', 'show', 'update', 'delete', 'pagination'];
        $access[1]['Profile'] = ['index', 'update-password', 'update-admin-app'];

        $access[2]['Dashboard'] = ['index'];
        $access[2]['Branch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination'];
        $access[2]['AdminHub'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[2]['AdminBranch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[2]['Expert'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel', 'confirmation'];
        $access[2]['CompanyMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $access[2]['WelderMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $access[2]['WelderSkill'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[2]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[2]['ExamPacket'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[2]['Exam'] = ['index', 'create', 'show', 'update', 'delete', 'pagination'];
        $access[2]['Profile'] = ['index', 'update-password', 'update-admin-hub'];

        $access[3]['Dashboard'] = ['index'];
        $access[3]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[3]['Profile'] = ['index', 'update-password', 'update-admin-branch'];

        $access[4]['Dashboard'] = ['index'];
        $access[4]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[4]['ExamPacket'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[4]['Exam'] = ['index', 'create', 'show', 'update', 'delete', 'pagination'];
        $access[4]['Profile'] = ['index', 'update-password', 'update-expert'];

        $access[5]['Dashboard'] = ['index'];
        $access[5]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[5]['Profile'] = ['index', 'update-password', 'update-company-member'];

        $access[6]['Dashboard'] = ['index'];
        $access[6]['Article'] = ['index', 'index-guest', 'show', 'search', 'pagination'];
        $access[6]['Profile'] = ['index', 'update-password', 'update-welder-member'];

        $access[7]['Dashboard'] = ['index'];
        $access[7]['Article'] = ['index', 'index-guest', 'show', 'search', 'pagination'];
        $access[7]['Profile'] = ['index', 'update-password', 'update-guest'];

        $this->disableForeignKeys();
        $this->truncate('roles');
        $this->truncate('permissions');

        $permission["Dashboard"] = ['index'];
        $permission['Branch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination'];
        $permission['AdminHub'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $permission['AdminBranch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $permission['Expert'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel', 'confirmation'];
        $permission['CompanyMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $permission['WelderMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $permission['WelderSkill'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $permission['Article'] = ['index', 'index-guest', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $permission['ExamPacket'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $permission['Exam'] = ['index', 'create', 'show', 'update', 'delete', 'pagination'];
        $permission['Profile'] = ['index', 'update-password', 'update-admin-app', 'update-admin-hub', 'update-admin-branch', 'update-expert', 'update-company-member', 'update-welder-member', 'update-guest'];

        foreach ($permission as $key => $item) {
            foreach ($item as $permission) {
                Permission::create([
                    'name' => strtolower($key) . '.' . $permission,
                    'guard_name' => 'api'
                ]);
            }
        }

        foreach ($roles as $role) {
            $role = Role::create([
                'name' => $role,
                'guard_name' => 'api'
            ]);

            if (isset($access[$role->id])) {

                $permissionToRole = [];

                foreach ($access[$role->id] as $keys => $perm) {
                    foreach ($perm as $accessPermission) {
                        $permissionToRole[] = strtolower($keys) . '.' . $accessPermission;
                    }
                }

                $perms = Permission::whereIn('name', $permissionToRole)->pluck('name');
                $role->syncPermissions($perms);
            }
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
                $user->assignRole(Role::where('id', ModelsRole::MEMBER_WELDER)->first());
                $user->assignRole(Role::where('id', ModelsRole::GUEST)->first());
            }

            if ($user->role_id == ModelsRole::MEMBER_COMPANY) {
                $user->assignRole(Role::where('id', ModelsRole::MEMBER_COMPANY)->first());
                $user->assignRole(Role::where('id', ModelsRole::GUEST)->first());
            }

            if ($user->role_id == ModelsRole::MEMBER_WELDER) {
                $user->assignRole(Role::where('id', ModelsRole::MEMBER_WELDER)->first());
                $user->assignRole(Role::where('id', ModelsRole::GUEST)->first());
            }

            if ($user->role_id == ModelsRole::GUEST) {
                $user->assignRole(Role::where('id', ModelsRole::GUEST)->first());
            }
        }
    }
}
