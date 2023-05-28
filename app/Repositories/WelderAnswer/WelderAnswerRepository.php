<?php

namespace App\Repositories\WelderAnswer;

use LaravelEasyRepository\Repository;

interface WelderAnswerRepository extends Repository
{
    public function query();

    public function where(array $data);

    public function updateOrCreate(array $data, array $data2);
}
