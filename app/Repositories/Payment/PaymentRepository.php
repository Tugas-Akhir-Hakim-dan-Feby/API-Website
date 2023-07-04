<?php

namespace App\Repositories\Payment;

use LaravelEasyRepository\Repository;

interface PaymentRepository extends Repository
{
    public function query();

    public function paymentUsers();

    public function findByCriteria(array $data);
}
