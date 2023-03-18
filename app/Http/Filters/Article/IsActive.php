<?php

namespace App\Http\Filters\Article;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class IsActive
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('is_active')) {
            return $next($query);
        }

        if (request('is_active') == "true") {
            $query->where('status', 1);
        }

        if (request('is_active') == "false") {
            $query->where('status', 0);
        }

        return $next($query);
    }
}
