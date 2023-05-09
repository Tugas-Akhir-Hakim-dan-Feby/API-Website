<?php

namespace App\Http\Traits;

use App\Models\Role;

trait CheckRoles
{
    public function isAdminApp()
    {
        if ($this->role_id == Role::ADMIN_APP) {
            return true;
        }

        return false;
    }

    public function isAdminHub()
    {
        if ($this->role_id == Role::ADMIN_PUSAT) {
            return true;
        }

        return false;
    }

    public function isAdminBranch()
    {
        if ($this->role_id == Role::ADMIN_CABANG) {
            return true;
        }

        return false;
    }

    public function isExpert()
    {
        if ($this->role_id == Role::PAKAR) {
            return true;
        }

        return false;
    }

    public function isMemberCompany()
    {
        if ($this->role_id == Role::MEMBER_COMPANY) {
            return true;
        }

        return false;
    }

    public function isMemberWelder()
    {
        if ($this->role_id == Role::MEMBER_WELDER) {
            return true;
        }

        return false;
    }

    public function onlyRoles($roles)
    {
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($role == $this->role_id) {
                    return true;
                }
            }

            return false;
        }
    }
}
