<?php

namespace App\Repositories\CompanyMember;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User\CompanyMember;

class CompanyMemberRepositoryImplement extends Eloquent implements CompanyMemberRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(CompanyMember $model)
    {
        $this->model = $model;
    }

    public function findOrFail($id)
    {
        $companyMember = $this->model->where("uuid", $id)->first();

        if (!$companyMember) {
            abort(404);
        }

        return $companyMember;
    }
}
