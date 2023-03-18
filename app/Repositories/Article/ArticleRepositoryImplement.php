<?php

namespace App\Repositories\Article;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Article;

class ArticleRepositoryImplement extends Eloquent implements ArticleRepository
{

    /**
     * Model class to be used in this repository for the common methods inside Eloquent
     * Don't remove or change $this->model variable name
     * @property Model|mixed $model;
     */
    protected $model;

    public function __construct(Article $model)
    {
        $this->model = $model;
    }

    public function query()
    {
        return $this->model->query();
    }

    public function findByCriteria(array $data)
    {
        $article = $this->model->where($data)->first();

        if (!$article) {
            abort(404);
        }

        return $article;
    }

    public function findOrFail($id)
    {
        $article = $this->model->where('uuid', $id)->first();

        if (!$article) {
            abort(404);
        }

        return $article;
    }
}
