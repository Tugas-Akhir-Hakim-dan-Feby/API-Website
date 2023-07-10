<?php

namespace App\Repositories\PersonalData;

use LaravelEasyRepository\Repository;

interface PersonalDataRepository extends Repository
{
    public function findByCriteria(array $data);

    public function getFillable();
}
