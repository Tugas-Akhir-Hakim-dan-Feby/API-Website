<?php

namespace App\Repositories\RegisterJob;

use LaravelEasyRepository\Repository;

interface RegisterJobRepository extends Repository
{
    public function query();

    public function findByCriteria(array $data);
}
