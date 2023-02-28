<?php

namespace App\Repositories\Branch;

use LaravelEasyRepository\Repository;

interface BranchRepository extends Repository
{
    public function query();
}
