<?php

namespace App\Repositories\UserExpert;

use App\Models\User\Expert;
use LaravelEasyRepository\Implementations\Eloquent;

class UserExpertRepositoryImplement extends Eloquent implements UserExpertRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Expert $model)
    {
        $this->model = $model;
    }

    public function where(array $data)
    {
        return $this->model->where($data);
    }
}
