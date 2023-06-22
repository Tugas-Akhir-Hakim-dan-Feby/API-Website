<?php

namespace App\Http\Filters\WelderHasExamPacket;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Search
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('search')) {
            return $next($query);
        }

        $query->whereHas('user', function ($query) {
            $query->where('name', 'like', '%' . request('search') . '%');
        });

        return $next($query);
    }
}
