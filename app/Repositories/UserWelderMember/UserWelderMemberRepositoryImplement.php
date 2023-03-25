<?php

namespace App\Repositories\UserWelderMember;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User\WelderMember;

class UserWelderMemberRepositoryImplement extends Eloquent implements UserWelderMemberRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(WelderMember $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)
}
