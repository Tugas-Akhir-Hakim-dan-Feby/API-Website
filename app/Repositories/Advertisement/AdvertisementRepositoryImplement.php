<?php

namespace App\Repositories\Advertisement;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Advertisement;

class AdvertisementRepositoryImplement extends Eloquent implements AdvertisementRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Advertisement $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->query();
    }

    public function findWhere(array $data)
    {
        return $this->model->where($data)->first();
    }
}
