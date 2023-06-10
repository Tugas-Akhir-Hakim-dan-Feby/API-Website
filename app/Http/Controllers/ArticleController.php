<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Article\ArticleCollection;
use App\Http\Resources\Article\ArticleDetail;
use App\Repositories\User\UserRepository;
use App\Http\Requests\Article\ArticleRequestStore;
use App\Repositories\Article\ArticleRepository;
use App\Models\Role;

class ArticleController extends Controller
{
    protected $userRepository, $articleRepository;

    public function __construct(UserRepository $userRepository, ArticleRepository $articleRepository)
    {
        $this->userRepository = $userRepository;
        $this->articleRepository = $articleRepository;
    }

    public function index(Request $request)
    {
        $articles = $this->userRepository->findByCriteria(['role_id' =>Role::ARTICLE]);
        return new ArticleCollection($articles);
    }

    public function store(ArticleRequestStore $request)
    {
        return DB::transaction(function () use ($request) {
        return $this->articleRepository->create($request->all());
        });
    }

    public function show($id)
    {
        $articles = $this->userRepository->findOrFail($id);
        return new ArticleDetail($articles);
    }

    public function update(ArticleRequestStore $request, $id)
    {
        $articles = $this->userRepository->findOrFail($id);

        return DB::transaction(function () use ($request, $articles) {
            $this->userRepository->update($articles->id, $request->all());
            return $this->articleRepository->update($articles->article->id, $request->all());
        });
    }

    public function destroy($id)
    {
        $user = $this->userRepository->findOrFail($id);

        return DB::transaction(function () use ($user) {
            if ($user->article){
                $user->article->delete();
            }

            return $user->delete();
        });
    }
}

