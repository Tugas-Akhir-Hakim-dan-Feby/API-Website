<?php

namespace App\Http\Controllers;

use App\Http\Filters\Article\Author;
use App\Http\Filters\Article\IsActive;
use App\Http\Filters\Article\Search;
use App\Http\Requests\Article\ArticleRequestStore;
use App\Http\Requests\Article\ArticleRequestUpdate;
use App\Http\Resources\Article\ArticleCollection;
use App\Http\Resources\Article\ArticleDetail;
use App\Http\Traits\MessageFixer;
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
    use UploadDocument, MessageFixer;

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
        DB::beginTransaction();

        $request->merge([
            'uuid' => Str::uuid(),
            'user_id' => Auth::user()->id,
            'article_slug' => Str::slug($request->article_title),
            'status' => $request->status ? Article::ACTIVE : Article::INACTIVE
        ]);

        try {
            $article = $this->articleRepository->create($request->all());

            $this->upload($request->document, $article->document(), 'article');

            DB::commit();

            return $this->createMessage("data berhasil ditambahkan", $article);
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorMessage($th->getMessage());
        }
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
        DB::beginTransaction();

        $article = $this->articleRepository->findOrFail($id);

        $request->merge([
            'article_slug' => Str::slug($request->article_title),
        ]);

        try {
            if ($request->hasFile('document')) {
                if ($article->document) {
                    $path = str_replace(url('storage') . '/', '', $article->document->document_path);
                    Storage::delete($path);

                    $article->document()->delete();
                }

                $this->upload($request->document, $article->document(), 'article');
            }

            $article->update($request->all());

            DB::commit();

            return $this->successMessage("data berhasil diperbaharui", $article);
        } catch (\Throwable $th) {
            DB::rollback();

            return $this->errorMessage($th->getMessage());
        }
    }

    public function updateStatus($id)
    {
        DB::beginTransaction();

        $article = $this->articleRepository->findOrFail($id);

        try {
            $article->update([
                'status' => $article->status ? Article::INACTIVE : Article::ACTIVE
            ]);

            DB::commit();

            return $this->successMessage("data berhasil diperbaharui", $article);
        } catch (\Throwable $th) {
            DB::rollback();

            return $this->errorMessage($th->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        $article = $this->articleRepository->findOrFail($id);

        try {
            if ($article->document) {
                $path = str_replace(url('storage') . '/', '', $article->document->document_path);
                Storage::delete($path);

                $article->document()->delete();
            }

            $article->delete();

            DB::commit();

            return $this->successMessage("data berhasil dihapus", $article);
        } catch (\Throwable $th) {
            DB::rollback();

            return $this->errorMessage($th->getMessage());
        }
    }
}
