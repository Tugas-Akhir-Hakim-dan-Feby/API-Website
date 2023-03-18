<?php

namespace App\Repositories\Article;

use LaravelEasyRepository\Repository;

interface ArticleRepository extends Repository
{
    public function query();

    public function findByCriteria(array $data);
}
