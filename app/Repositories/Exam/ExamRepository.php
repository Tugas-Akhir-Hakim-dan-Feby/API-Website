<?php

namespace App\Repositories\Exam;

use LaravelEasyRepository\Repository;

interface ExamRepository extends Repository
{
    public function query();
}
