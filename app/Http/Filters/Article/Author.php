<?php

namespace App\Http\Filters\Article;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Author
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('author')) {
            return $next($query);
        }
        $query->whereHas("user", function ($query) {
            $query->where("name", "LIKE", "%" . request('author') . "%");
        });

        return $next($query);
    }
}
