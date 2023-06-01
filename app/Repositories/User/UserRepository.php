<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Repository;

interface UserRepository extends Repository
{
    public function findByEmail(string $email);
    public function findByCriteria(array $data);
    public function getFillable();
    public function query();
}
