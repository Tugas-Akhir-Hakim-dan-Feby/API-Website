<?php

namespace App\Http\Filters\Article;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Search
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('search')) {
            return $next($query);
        }

        $query->where(function ($query) {
            $query->where('article_title', 'LIKE', '%' . request('search') . '%');
            $query->orWhere('article_content', 'LIKE', '%' . request('search') . '%');
        });

        return $next($query);
    }
}
