<?php

namespace App\Repositories\PersonalData;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\PersonalData;

class PersonalDataRepositoryImplement extends Eloquent implements PersonalDataRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(PersonalData $model)
    {
        $this->model = $model;
    }

    public function findByCriteria(array $data)
    {
        $personalData = $this->model->where($data)->first();

        return $personalData;
    }

    public function getFillable()
    {
        return $this->model->getFillable();
    }
}
