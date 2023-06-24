<?php

namespace App\Repositories\Cost;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Cost;

class CostRepositoryImplement extends Eloquent implements CostRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Cost $model)
    {
        $this->model = $model;
    }

    public function findOrFail($id)
    {
        $cost = $this->model->where("uuid", $id)->first();

        if (!$cost) {
            abort(404);
        }

        return $cost;
    }
}
