<?php

namespace App\Repositories\Welder;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Welder;

class WelderRepositoryImplement extends Eloquent implements WelderRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(Welder $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->query();
    }
}
