<?php

namespace App\Repositories\UserOperator;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User\Operator;

class UserOperatorRepositoryImplement extends Eloquent implements UserOperatorRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Operator $model)
    {
        $this->model = $model;
    }

    public function findOrFail($id)
    {
        return $this->model->where('uuid', $id)->first();
    }
}
