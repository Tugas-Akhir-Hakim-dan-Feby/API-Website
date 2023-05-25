<?php

namespace App\Repositories\Answer;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Answer;

class AnswerRepositoryImplement extends Eloquent implements AnswerRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Answer $model)
    {
        $this->model = $model;
    }

    public function where(array $data)
    {
        return $this->model->where($data);
    }
}
