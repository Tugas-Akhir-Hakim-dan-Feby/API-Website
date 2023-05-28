<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\User;

class UserRepositoryImplement extends Eloquent implements UserRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findByEmail(string $email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findByCriteria(array $data)
    {
        return $this->model->where($data)->first();
    }

    public function findOrFail($id)
    {
        return $this->model->where('uuid', $id)->first();
    }

    public function query()
    {
        return $this->model->query();
    }

    public function getFillable()
    {
        return $this->model->getFillable();
    }
}
