<?php

namespace App\Repositories\WelderAnswer;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\WelderAnswer;

class WelderAnswerRepositoryImplement extends Eloquent implements WelderAnswerRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(WelderAnswer $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->query();
    }

    public function where(array $data)
    {
        return $this->model->where($data);
    }

    public function updateOrCreate(array $data1, array $data2)
    {
        return $this->model->updateOrCreate($data1, $data2);
    }
}
