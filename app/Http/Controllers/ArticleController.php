<?php

namespace App\Http\Controllers;

use App\Http\Filters\Article\Author;
use App\Http\Filters\Article\IsActive;
use App\Http\Filters\Article\Search;
use App\Http\Requests\Article\ArticleRequestStore;
use App\Http\Requests\Article\ArticleRequestUpdate;
use App\Http\Resources\Article\ArticleCollection;
use App\Http\Resources\Article\ArticleDetail;
use App\Http\Traits\UploadDocument;
use App\Models\Article;
use App\Repositories\Article\ArticleRepository;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    use UploadDocument;

    protected $articleRepository;

    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index(Request $request)
    {
        $articles = app(Pipeline::class)
            ->send($this->articleRepository->query())
            ->through([
                IsActive::class,
                Search::class,
                Author::class
            ])
            ->thenReturn()
            ->with(['document', 'user'])
            ->paginate($request->per_page);

        return new ArticleCollection($articles);
    }

    public function store(ArticleRequestStore $request)
    {
        $request->merge([
            'uuid' => Str::uuid(),
            'user_id' => Auth::user()->id,
            'article_slug' => Str::slug($request->article_title),
            'status' => $request->status ? Article::ACTIVE : Article::INACTIVE
        ]);

        return DB::transaction(function () use ($request) {
            $article = $this->articleRepository->create($request->all());

            $this->upload($request->document, $article->document(), 'article');

            return $article;
        });
    }

    public function showBySlug($slug)
    {
        $article = $this->articleRepository->findByCriteria(["article_slug" => $slug]);
        $article->load(['document', 'user']);

        return new ArticleDetail($article);
    }

    public function show($id)
    {
        $article = $this->articleRepository->findOrFail($id);
        $article->load(['document', 'user']);

        return new ArticleDetail($article);
    }

    public function update(ArticleRequestUpdate $request, $id)
    {
        $article = $this->articleRepository->findOrFail($id);

        $request->merge([
            'article_slug' => Str::slug($request->article_title),
        ]);

        return DB::transaction(function () use ($request, $article) {
            if ($request->hasFile('document')) {
                if ($article->document) {
                    $path = str_replace(url('storage') . '/', '', $article->document->document_path);
                    Storage::delete($path);

                    $article->document()->delete();
                }

                $this->upload($request->document, $article->document(), 'article');
            }

            return $article->update($request->all());
        });
    }

    public function destroy($id)
    {
        $article = $this->articleRepository->findOrFail($id);
        if ($article->document) {
            $path = str_replace(url('storage') . '/', '', $article->document->document_path);
            Storage::delete($path);

            $article->document()->delete();
        }

        return $article->delete();
    }
}
