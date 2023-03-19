<?php

namespace App\Repositories\UserBranch;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User\Branch;

class UserBranchRepositoryImplement extends Eloquent implements UserBranchRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Branch $model)
    {
        $this->model = $model;
    }

    public function findByCriteria(array $data)
    {
        $user = $this->model->where($data)->first();

        if (!$user) {
            abort(404);
        }

        return $user;
    }

    public function getFillable()
    {
        return $this->model->getFillable();
    }
}
