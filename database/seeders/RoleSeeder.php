<?php

namespace Database\Seeders;

use App\Models\Role as ModelsRole;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\Traits\DisableForeignKey;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    use DisableForeignKey, TruncateTable;

    public function run()
    {
        $roles = ['Admin App', 'Admin Pusat', 'Admin Cabang', 'Pakar', 'Member Company', 'Member Welder', 'Guest'];

        $access[User::ADMIN_APP]['Dashboard'] = ['index'];
        $access[User::ADMIN_APP]['Branch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_APP]['AdminHub'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[User::ADMIN_APP]['AdminBranch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[User::ADMIN_APP]['Expert'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel', 'confirmation'];
        $access[User::ADMIN_APP]['CompanyMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $access[User::ADMIN_APP]['WelderMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $access[User::ADMIN_APP]['WelderSkill'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_APP]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_APP]['ExamPacket'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'dashboard-admin'];
        $access[User::ADMIN_APP]['Exam'] = ['index', 'create', 'show', 'update', 'delete', 'pagination'];
        $access[User::ADMIN_APP]['Profile'] = ['index', 'update-password', 'update-admin-app'];
        $access[User::ADMIN_APP]['Payment'] = ['cost', 'invoice'];
        $access[User::ADMIN_APP]['JobVacancy'] = ['index', 'index-admin', 'info-company', 'create', 'show', 'update', 'delete'];

        $access[User::ADMIN_PUSAT]['Dashboard'] = ['index'];
        $access[User::ADMIN_PUSAT]['Branch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_PUSAT]['AdminHub'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[User::ADMIN_PUSAT]['AdminBranch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[User::ADMIN_PUSAT]['Expert'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel', 'confirmation'];
        $access[User::ADMIN_PUSAT]['CompanyMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $access[User::ADMIN_PUSAT]['WelderMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $access[User::ADMIN_PUSAT]['WelderSkill'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_PUSAT]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_PUSAT]['ExamPacket'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[User::ADMIN_PUSAT]['Exam'] = ['index', 'create', 'show', 'update', 'delete', 'pagination'];
        $access[User::ADMIN_PUSAT]['Profile'] = ['index', 'update-password', 'update-admin-hub'];
        $access[User::ADMIN_PUSAT]['Payment'] = ['cost', 'invoice'];
        $access[User::ADMIN_PUSAT]['JobVacancy'] = ['index', 'index-admin', 'info-company', 'create', 'show', 'update', 'delete'];

        $access[User::ADMIN_CABANG]['Dashboard'] = ['index'];
        $access[User::ADMIN_CABANG]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_CABANG]['Profile'] = ['index', 'update-password', 'update-admin-branch'];

        $access[User::PAKAR]['Dashboard'] = ['index'];
        $access[User::PAKAR]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::PAKAR]['ExamPacket'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'dashboard-admin'];
        $access[User::PAKAR]['Exam'] = ['index', 'create', 'show', 'update', 'delete', 'pagination'];
        $access[User::PAKAR]['Profile'] = ['index', 'update-password', 'update-expert'];

        $access[User::MEMBER_COMPANY]['Dashboard'] = ['index'];
        $access[User::MEMBER_COMPANY]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::MEMBER_COMPANY]['Profile'] = ['index', 'update-password', 'update-company-member'];
        $access[User::MEMBER_COMPANY]['Payment'] = ['history'];
        $access[User::MEMBER_COMPANY]['JobVacancy'] = ['index', 'index-admin', 'info', 'create', 'show', 'update', 'delete'];

        $access[User::MEMBER_WELDER]['Dashboard'] = ['index'];
        $access[User::MEMBER_WELDER]['Article'] = ['index', 'index-guest', 'show', 'search', 'pagination'];
        $access[User::MEMBER_WELDER]['Profile'] = ['index', 'update-password', 'update-welder-member'];
        $access[User::MEMBER_WELDER]['ExamPacket'] = ['index', 'show', 'pagination', 'register-packet', 'dashboard-member'];
        $access[User::MEMBER_WELDER]['Exam'] = ['index', 'show', 'pagination'];
        $access[User::MEMBER_WELDER]['Payment'] = ['history'];
        $access[User::MEMBER_WELDER]['Member'] = ['expert'];
        $access[User::MEMBER_WELDER]['JobVacancy'] = ['index', 'index-welder'];

        $access[User::GUEST]['Dashboard'] = ['index'];
        $access[User::GUEST]['Article'] = ['index', 'index-guest', 'show', 'search', 'pagination'];
        $access[User::GUEST]['Profile'] = ['index', 'update-password', 'update-guest'];
        $access[User::GUEST]['Member'] = ['index', 'company-member', 'welder-member'];
        $access[User::GUEST]['JobVacancy'] = ['index', 'index-welder'];

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
        $permission['ExamPacket'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'register-packet', 'dashboard-admin', 'dashboard-member'];
        $permission['Exam'] = ['index', 'create', 'show', 'update', 'delete', 'pagination'];
        $permission['Profile'] = ['index', 'update-password', 'update-admin-app', 'update-admin-hub', 'update-admin-branch', 'update-expert', 'update-company-member', 'update-welder-member', 'update-guest'];
        $permission['Payment'] = ['cost', 'invoice', 'history'];
        $permission['Member'] = ['index', 'company-member', 'welder-member', 'expert'];
        $permission['JobVacancy'] = ['index', 'index-welder', 'index-admin', 'info', 'info-company', 'create', 'show', 'update', 'delete'];

        foreach ($permission as $key => $item) {
            foreach ($item as $permission) {
                DB::table('permissions')->insert([
                    'name' => strtolower($key) . '.' . $permission,
                    'guard_name' => 'api',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
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

        $this->enableForeignKeys();
    }
}
