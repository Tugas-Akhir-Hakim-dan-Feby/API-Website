<?php

namespace App\Repositories\JobVacancy;

use LaravelEasyRepository\Repository;

interface JobVacancyRepository extends Repository
{
    public function query();

    public function findByCriteria(array $data);
}
