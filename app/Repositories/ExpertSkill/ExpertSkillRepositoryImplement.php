<?php

namespace App\Repositories\ExpertSkill;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\ExpertSkill;

class ExpertSkillRepositoryImplement extends Eloquent implements ExpertSkillRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(ExpertSkill $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->query();
    }

    public function findOrFail($id)
    {
        $branch = $this->model->where('uuid', $id)->first();

        if (!$branch) {
            abort(404);
        }

        return $branch;
    }
}
