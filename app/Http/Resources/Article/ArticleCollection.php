<?php

namespace App\Http\Resources\Article;

use App\Http\Traits\MessageFixer;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;

class ArticleCollection extends ResourceCollection
{
    use MessageFixer;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [];

        foreach ($this as $article) {
            $data[] = [
                "uuid" => $article->uuid,
                "article_title" => $article->article_title,
                "article_content" => $article->article_content,
                "article_slug" => $article->article_slug,
                "article_excerpt" => Str::limit(strip_tags($article->article_content), 200),
                "document" => $article->document,
                "user" => $article->user,
                "created_at" => $article->created_at,
                "updated_at" => $article->updated_at,
            ];
        }

        return [
            "status" => "SUCCESS",
            "message" => "ambil data berhasil",
            "status_code" => Response::HTTP_OK,
            "data" => $data,
        ];
    }
}
