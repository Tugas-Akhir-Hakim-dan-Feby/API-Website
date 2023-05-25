<?php

namespace App\Repositories\Answer;

use LaravelEasyRepository\Repository;

interface AnswerRepository extends Repository
{
    public function where(array $data);
}
