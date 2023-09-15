<?php

namespace App\Repositories\WelderSkill;

use LaravelEasyRepository\Repository;

interface WelderSkillRepository extends Repository
{
    public function query();

    public function whereIn(array $data);
}
