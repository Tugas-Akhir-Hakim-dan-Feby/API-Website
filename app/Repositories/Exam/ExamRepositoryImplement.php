<?php

namespace App\Repositories\Exam;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Exam;

class ExamRepositoryImplement extends Eloquent implements ExamRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Exam $model)
    {
        $this->model = $model;
    }

    public function findOrFail($id)
    {
        $exam = $this->model->where("uuid", $id)->first();

        if (!$exam) {
            abort(404);
        }

        return $exam;
    }
}
