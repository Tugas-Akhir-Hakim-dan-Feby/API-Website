<?php

namespace App\Repositories\JobVacancy;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\JobVacancy;

class JobVacancyRepositoryImplement extends Eloquent implements JobVacancyRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(JobVacancy $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->query();
    }

    public function findOrFail($id)
    {
        $jobVacancy = $this->model->where("uuid", $id)->first();

        if (!$jobVacancy) {
            abort(404);
        }

        return $jobVacancy;
    }

    public function findByCriteria(array $data)
    {
        $jobVacancy = $this->model->where($data)->first();

        if (!$jobVacancy) {
            abort(404);
        }

        return $jobVacancy;
    }
}
