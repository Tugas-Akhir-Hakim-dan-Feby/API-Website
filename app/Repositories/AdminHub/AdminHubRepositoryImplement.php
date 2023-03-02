<?php

namespace App\Repositories\AdminHub;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\AdminHub;

class AdminHubRepositoryImplement extends Eloquent implements AdminHubRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(AdminHub $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->query();
    }
}
