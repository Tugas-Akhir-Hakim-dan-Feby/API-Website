<?php

namespace App\Repositories\UserBranch;

use LaravelEasyRepository\Repository;

interface UserBranchRepository extends Repository
{
    public function findByCriteria(array $data);

    public function getFillable();
}
