<?php

namespace App\Repositories\Advertisement;

use LaravelEasyRepository\Repository;

interface AdvertisementRepository extends Repository
{
    public function query();

    public function findWhere(array $data);
}
