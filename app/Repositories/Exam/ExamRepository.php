<?php

namespace App\Repositories\Exam;

use LaravelEasyRepository\Repository;

interface ExamRepository extends Repository
{
    public function query();
    public function where(array $data);
}
