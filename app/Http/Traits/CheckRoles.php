<?php

namespace App\Http\Traits;

use App\Models\Role;
use App\Models\User;

trait CheckRoles
{
    public function isAdminApp()
    {
        if ($this->role_id == User::ADMIN_APP) {
            return true;
        }

        return false;
    }

    public function isAdminHub()
    {
        if ($this->role_id == User::ADMIN_HUB) {
            return true;
        }

        return false;
    }

    public function isAdminBranch()
    {
        if ($this->role_id == User::ADMIN_BRANCH) {
            return true;
        }

        return false;
    }

    public function isExpert()
    {
        if ($this->role_id == User::EXPERT) {
            return true;
        }

        return false;
    }

    public function isMemberCompany()
    {
        if ($this->role_id == User::MEMBER_COMPANY) {
            return true;
        }

        return false;
    }

    public function isMemberWelder()
    {
        if ($this->role_id == User::MEMBER_INDIVIDUAL) {
            return true;
        }

        return false;
    }

    public function isOperator()
    {
        if ($this->role_id == User::OPERATOR) {
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
