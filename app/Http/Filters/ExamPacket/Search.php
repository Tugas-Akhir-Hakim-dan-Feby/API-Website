<?php

namespace App\Http\Filters\ExamPacket;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Search
{
    public function handle(Builder $query, Closure $next)
    {
        $query->with("user.operator");

        if (!request('search')) {
            return $next($query);
        }

        $query->where('name', 'LIKE', '%' . request('search') . '%');

        return $next($query);
    }
}
