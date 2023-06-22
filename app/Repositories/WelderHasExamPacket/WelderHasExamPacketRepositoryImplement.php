<?php

namespace App\Repositories\WelderHasExamPacket;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\WelderHasExamPacket;

class WelderHasExamPacketRepositoryImplement extends Eloquent implements WelderHasExamPacketRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(WelderHasExamPacket $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->query();
    }

    public function findByCriteria($data)
    {
        return $this->model->where($data)->first();
    }
}
