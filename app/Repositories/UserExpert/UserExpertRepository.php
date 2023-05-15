<?php

namespace App\Repositories\UserExpert;

use LaravelEasyRepository\Repository;

interface UserExpertRepository extends Repository
{
    public function where(array $data);
}
