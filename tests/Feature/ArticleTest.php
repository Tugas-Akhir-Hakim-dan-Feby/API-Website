<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    /** @test */
    public function can_get_data_article_before_login()
    {
        $response = $this->getJson(route('api.article.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_get_data_article_with_param_search()
    {
        $response = $this->getJson(route('api.article.index', [
            'search' => 'te'
        ]));

        $result = collect($response->json()['data']);
        $this->assertTrue($result->count() > 0);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_get_data_article_with_param_author()
    {
        $user = User::find(1);

        $response = $this->getJson(route('api.article.index', [
            'author' => $user->name
        ]));

        $result = collect($response->json()['data']);
        $this->assertTrue($result->count() > 0);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_get_data_article_with_param_active()
    {
        $response = $this->getJson(route('api.article.index', [
            'is_active' => 'true'
        ]));

        $result = collect($response->json()['data']);
        $this->assertTrue($result->count() > 0);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_get_data_article_with_param_in_active()
    {
        $response = $this->getJson(route('api.article.index', [
            'is_active' => 'false'
        ]));

        $result = collect($response->json()['data']);
        $this->assertTrue($result->where('status', 0)->count() > 0);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function cannot_store_article_before_login()
    {
        $this->postJson(route('api.article.store'))->assertUnauthorized();
    }

    /** @test */
    public function cannot_store_article_without_params()
    {
        Sanctum::actingAs(User::find(1), ["admin-app"]);

        $this->postJson(route('api.article.store'))->assertBadRequest();
    }

    /** @test */
    public function cannot_store_article_without_image_document()
    {
        Sanctum::actingAs(User::find(1), ["admin-app"]);

        Storage::fake('article');
        $file = UploadedFile::fake()->create("article.pdf", 10);

        $this->postJson(route('api.article.store'), [
            "article_title" => "coba",
            "article_content" => "coba content",
            "document" => $file,
            "status" => 1
        ])->assertBadRequest();
    }

    /** @test */
    public function can_store_article()
    {
        Sanctum::actingAs(User::find(1), ["admin-app"]);

        Storage::fake('article');
        $file = UploadedFile::fake()->create("article.png", 10);

        $this->postJson(route('api.article.store'), [
            "article_title" => "coba",
            "article_content" => "coba content",
            "document" => $file,
            "status" => 1
        ])->assertCreated();

        $article = Article::where('article_title', "coba")->first();
        $this->assertNotNull($article);
    }

    /** @test */
    public function cannot_get_detail_article_before_login()
    {
        $this->getJson(route('api.article.show', [
            'id' => 12121212
        ]))->assertUnauthorized();
    }

    /** @test */
    public function cannot_get_detail_article_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ["admin-app"]);

        $this->getJson(route('api.article.show', [
            'id' => 12121212
        ]))->assertNotFound();
    }

    /** @test */
    public function can_get_detail_article_with_id()
    {
        Sanctum::actingAs(User::find(1), ["admin-app"]);

        $article = Article::where('article_title', "coba")->first();

        $this->getJson(route('api.article.show', [
            'id' => $article->uuid
        ]))->assertOk();
    }

    /** @test */
    public function can_not_update_data_article_before_login()
    {
        Storage::fake('article');
        $file = UploadedFile::fake()->create("article.pdf", 10);

        $article = Article::where('article_title', "coba")->first();

        $this->putJson(route('api.article.update', $article->uuid), [
            "article_title" => "coba EDITED",
            "article_content" => "coba content",
            "document" => $file,
            "status" => 1
        ])->assertUnauthorized();
    }

    /** @test */
    public function can_not_update_data_article_without_body()
    {
        Sanctum::actingAs(User::find(1), ["admin-app"]);

        Storage::fake('article');
        $file = UploadedFile::fake()->create("article.pdf", 10);

        $article = Article::where('article_title', "coba")->first();

        $this->putJson(route('api.article.update', $article->uuid))->assertBadRequest();
    }

    /** @test */
    public function can_not_update_data_article_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ["admin-app"]);

        Storage::fake('article');
        $file = UploadedFile::fake()->create("article.jpg", 10);

        $response = $this->putJson(route('api.article.update', 12121212), [
            "article_title" => "coba EDITED",
            "article_content" => "coba content",
            "document" => $file,
            "status" => 1
        ]);

        $response->assertNotFound();
    }

    /** @test */
    public function can_not_update_data_article_without_document_image()
    {
        Sanctum::actingAs(User::find(1), ["admin-app"]);

        Storage::fake('article');
        $file = UploadedFile::fake()->create("article.pdf", 10);

        $article = Article::where('article_title', "coba")->first();

        $this->putJson(route('api.article.update', $article->uuid), [
            "article_title" => "coba EDITED",
            "article_content" => "coba content",
            "document" => $file,
            "status" => 1
        ])->assertBadRequest();
    }

    /** @test */
    public function can_update_data_article()
    {
        Sanctum::actingAs(User::find(1), ["admin-app"]);

        Storage::fake('article');
        $file = UploadedFile::fake()->create("article.jpg", 10);

        $article = Article::where('article_title', "coba")->first();

        $this->putJson(route('api.article.update', $article->uuid), [
            "article_title" => "coba EDITED",
            "article_content" => "coba content",
            "document" => $file,
            "status" => 1
        ])->assertOk();

        $this->assertNull(Article::where('article_title', "coba")->first());
        $this->assertNotNull(Article::where('article_title', "coba EDITED")->first());
    }

    /** @test */
    public function can_not_delete_data_article_before_login()
    {
        $response = $this->deleteJson(route('api.article.destroy', 1));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    /** @test */
    public function can_not_delete_data_article_with_id_not_found()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $response = $this->deleteJson(route('api.article.destroy', 999));

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function can_delete_data_article()
    {
        Sanctum::actingAs(User::find(1), ['*']);

        $article = Article::where('article_title', "coba EDITED")->first();

        $response = $this->deleteJson(route('api.article.destroy', $article->uuid));

        $article = Article::where('article_title', "coba EDITED")->first();
        $this->assertNull($article);

        $response->assertStatus(Response::HTTP_OK);
    }
}
