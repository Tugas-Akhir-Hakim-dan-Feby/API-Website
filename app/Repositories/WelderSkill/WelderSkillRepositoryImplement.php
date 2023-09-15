<?php

namespace App\Repositories\WelderSkill;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\WelderSkill;

class WelderSkillRepositoryImplement extends Eloquent implements WelderSkillRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(WelderSkill $model)
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

    public function whereIn(array $data)
    {
        $skills = $this->model->whereIn('uuid', $data)->get();

        return $skills;
    }
}
