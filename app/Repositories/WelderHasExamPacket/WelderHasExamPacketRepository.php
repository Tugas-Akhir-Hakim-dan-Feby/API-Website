<?php

namespace App\Repositories\WelderHasExamPacket;

use LaravelEasyRepository\Repository;

interface WelderHasExamPacketRepository extends Repository
{
    public function query();
    public function findByCriteria($data);
}
