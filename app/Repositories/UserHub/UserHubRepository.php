<?php

namespace App\Repositories\UserHub;

use LaravelEasyRepository\Repository;

interface UserHubRepository extends Repository
{
    public function query();

    public function getFillable();
}
