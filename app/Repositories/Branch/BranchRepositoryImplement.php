<?php

namespace App\Repositories\Branch;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Branch;

class BranchRepositoryImplement extends Eloquent implements BranchRepository{

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

    // Write something awesome :)
}
