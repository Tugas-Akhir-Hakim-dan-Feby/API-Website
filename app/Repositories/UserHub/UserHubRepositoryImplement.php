<?php

namespace App\Repositories\UserHub;

use App\Models\User\Hub;
use LaravelEasyRepository\Implementations\Eloquent;

class UserHubRepositoryImplement extends Eloquent implements UserHubRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Hub $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->query();
    }

    public function getFillable()
    {
        return $this->model->getFillable();
    }
}
