<?php

namespace App\Repositories\RegisterJob;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\RegisterJob;

class RegisterJobRepositoryImplement extends Eloquent implements RegisterJobRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(RegisterJob $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->query();
    }

    public function findByCriteria(array $data)
    {
        $registerJob = $this->model->where($data)->first();

        return $registerJob;
    }
}
