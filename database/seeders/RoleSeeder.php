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
        $roles = ['Admin App', 'Admin Pusat', 'Admin Cabang', 'Pakar', 'Member Company', 'Member Individu', 'Member Aplikasi', 'Operator'];

        $access[User::ADMIN_APP]['Dashboard'] = ['index'];
        $access[User::ADMIN_APP]['Branch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_APP]['AdminHub'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[User::ADMIN_APP]['AdminBranch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[User::ADMIN_APP]['Expert'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel', 'confirmation'];
        $access[User::ADMIN_APP]['CompanyMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $access[User::ADMIN_APP]['WelderMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $access[User::ADMIN_APP]['WelderSkill'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_APP]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_APP]['ExamPacket'] = ['index', 'show', 'delete', 'search', 'pagination', 'update-status', 'dashboard-admin'];
        $access[User::ADMIN_APP]['Exam'] = ['index', 'create', 'show', 'update', 'delete', 'pagination'];
        $access[User::ADMIN_APP]['Profile'] = ['index', 'update-password', 'update-admin-app'];
        $access[User::ADMIN_APP]['Payment'] = ['cost', 'invoice'];
        $access[User::ADMIN_APP]['JobVacancy'] = ['index', 'index-admin', 'info-company', 'create', 'show', 'update', 'delete'];
        $access[User::ADMIN_APP]['Advertisement'] = ['index-admin', 'show', 'delete'];

        $access[User::ADMIN_HUB]['Dashboard'] = ['index'];
        $access[User::ADMIN_HUB]['Branch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_HUB]['AdminHub'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[User::ADMIN_HUB]['AdminBranch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $access[User::ADMIN_HUB]['Expert'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel', 'confirmation'];
        $access[User::ADMIN_HUB]['CompanyMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $access[User::ADMIN_HUB]['WelderMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $access[User::ADMIN_HUB]['WelderSkill'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_HUB]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_HUB]['ExamPacket'] = ['index', 'show', 'delete', 'search', 'pagination', 'update-status', 'dashboard-admin'];
        $access[User::ADMIN_HUB]['Exam'] = ['index', 'create', 'show', 'update', 'delete', 'pagination'];
        $access[User::ADMIN_HUB]['Profile'] = ['index', 'update-password', 'update-admin-hub'];
        $access[User::ADMIN_HUB]['Payment'] = ['cost', 'invoice'];
        $access[User::ADMIN_HUB]['JobVacancy'] = ['index', 'index-admin', 'info-company', 'create', 'show', 'update', 'delete'];
        $access[User::ADMIN_HUB]['Advertisement'] = ['index-admin', 'show', 'delete'];

        $access[User::ADMIN_BRANCH]['Dashboard'] = ['index'];
        $access[User::ADMIN_BRANCH]['Branch'] = ['dashboard'];
        $access[User::ADMIN_BRANCH]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::ADMIN_BRANCH]['Profile'] = ['index', 'update-password', 'update-admin-branch'];

        $access[User::EXPERT]['Dashboard'] = ['index'];
        $access[User::EXPERT]['Article'] = ['index', 'index-guest', 'show', 'search', 'pagination'];
        $access[User::EXPERT]['Profile'] = ['index', 'update-password', 'update-expert'];
        $access[User::EXPERT]['Payment'] = ['history'];
        $access[User::EXPERT]['JobVacancy'] = ['index', 'index-welder', 'info', 'show', 'history'];
        $access[User::EXPERT]['Advertisement'] = ['index-guest', 'show', 'create', 'update', 'delete'];

        $access[User::MEMBER_COMPANY]['Dashboard'] = ['index'];
        $access[User::MEMBER_COMPANY]['Article'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $access[User::MEMBER_COMPANY]['Profile'] = ['index', 'update-password', 'update-company-member'];
        $access[User::MEMBER_COMPANY]['Payment'] = ['history'];
        $access[User::MEMBER_COMPANY]['JobVacancy'] = ['index', 'index-admin', 'info', 'create', 'show', 'update', 'delete', 'approval'];
        $access[User::MEMBER_COMPANY]['Advertisement'] = ['index-guest', 'show', 'create', 'update', 'delete'];

        $access[User::MEMBER_INDIVIDUAL]['Dashboard'] = ['index'];
        $access[User::MEMBER_INDIVIDUAL]['Article'] = ['index', 'index-guest', 'show', 'search', 'pagination'];
        $access[User::MEMBER_INDIVIDUAL]['Profile'] = ['index', 'update-password', 'update-welder-member'];
        $access[User::MEMBER_INDIVIDUAL]['ExamPacket'] = ['index', 'show', 'pagination', 'register-packet', 'dashboard-member'];
        $access[User::MEMBER_INDIVIDUAL]['Exam'] = ['index', 'show', 'pagination'];
        $access[User::MEMBER_INDIVIDUAL]['Payment'] = ['history'];
        $access[User::MEMBER_INDIVIDUAL]['Member'] = ['expert'];
        $access[User::MEMBER_INDIVIDUAL]['JobVacancy'] = ['index', 'index-welder', 'info', 'show', 'history'];
        $access[User::MEMBER_INDIVIDUAL]['Advertisement'] = ['index-guest', 'show', 'create', 'update', 'delete'];

        $access[User::MEMBER_APPLICATION]['Dashboard'] = ['index'];
        $access[User::MEMBER_APPLICATION]['Article'] = ['index', 'index-guest', 'show', 'search', 'pagination'];
        $access[User::MEMBER_APPLICATION]['Profile'] = ['index', 'update-password', 'update-welder-member'];
        $access[User::MEMBER_APPLICATION]['Member'] = ['index', 'company-member', 'welder-member'];
        $access[User::MEMBER_APPLICATION]['ExamPacket'] = ['index', 'show', 'pagination', 'register-packet', 'dashboard-member'];
        $access[User::MEMBER_APPLICATION]['Payment'] = ['history'];
        $access[User::MEMBER_APPLICATION]['Exam'] = ['index', 'show', 'pagination'];
        $access[User::MEMBER_APPLICATION]['Advertisement'] = ['index-guest', 'show', 'create', 'update', 'delete'];

        $access[User::OPERATOR]['Dashboard'] = ['index'];
        $access[User::OPERATOR]['Article'] = ['index', 'index-guest', 'show', 'search', 'pagination'];
        $access[User::OPERATOR]['Profile'] = ['index', 'update-password', 'update-guest'];
        $access[User::OPERATOR]['Member'] = ['index', 'company-member', 'welder-member'];
        $access[User::OPERATOR]['Payment'] = ['history'];
        $access[User::OPERATOR]['ExamPacket'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'dashboard-admin', 'evaluation', 'reset-key'];
        $access[User::OPERATOR]['Exam'] = ['index', 'create', 'show', 'update', 'delete', 'pagination'];
        $access[User::OPERATOR]['Advertisement'] = ['index-guest', 'show', 'create', 'update', 'delete'];

        $this->disableForeignKeys();
        $this->truncate('roles');
        $this->truncate('permissions');

        $permission["Dashboard"] = ['index'];
        $permission['Branch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'dashboard'];
        $permission['AdminHub'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $permission['AdminBranch'] = ['index', 'create', 'update', 'delete', 'search', 'pagination', 'update-status'];
        $permission['Expert'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel', 'confirmation'];
        $permission['CompanyMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $permission['WelderMember'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'upload-excel'];
        $permission['WelderSkill'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $permission['Article'] = ['index', 'index-guest', 'create', 'show', 'update', 'delete', 'search', 'pagination'];
        $permission['ExamPacket'] = ['index', 'create', 'show', 'update', 'delete', 'search', 'pagination', 'update-status', 'register-packet', 'dashboard-admin', 'dashboard-member', 'evaluation', 'reset-key'];
        $permission['Exam'] = ['index', 'create', 'show', 'update', 'delete', 'pagination'];
        $permission['Profile'] = ['index', 'update-password', 'update-admin-app', 'update-admin-hub', 'update-admin-branch', 'update-expert', 'update-company-member', 'update-welder-member', 'update-guest'];
        $permission['Payment'] = ['cost', 'invoice', 'history'];
        $permission['Member'] = ['index', 'company-member', 'welder-member', 'expert'];
        $permission['JobVacancy'] = ['index', 'index-welder', 'index-admin', 'info', 'info-company', 'create', 'show', 'update', 'delete', 'approval', 'history'];
        $permission['Advertisement'] = ['index-guest', 'index-admin', 'show', 'create', 'update', 'delete'];

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
            if ($user->role_id == User::ADMIN_APP) {
                $user->assignRole(Role::where('id', User::ADMIN_APP)->first());
            }

            if ($user->role_id == User::ADMIN_HUB) {
                $user->assignRole(Role::where('id', User::ADMIN_HUB)->first());
            }

            if ($user->role_id == User::ADMIN_BRANCH) {
                $user->assignRole(Role::where('id', User::ADMIN_BRANCH)->first());
            }

            if ($user->role_id == User::EXPERT) {
                $user->assignRole(Role::where('id', User::EXPERT)->first());
            }

            if ($user->role_id == User::MEMBER_COMPANY) {
                $user->assignRole(Role::where('id', User::MEMBER_COMPANY)->first());
            }

            if ($user->role_id == User::MEMBER_INDIVIDUAL) {
                $user->assignRole(Role::where('id', User::MEMBER_INDIVIDUAL)->first());
            }

            if ($user->role_id == User::MEMBER_APPLICATION) {
                $user->assignRole(Role::where('id', User::MEMBER_APPLICATION)->first());
            }
        }

        $this->enableForeignKeys();
    }
}
