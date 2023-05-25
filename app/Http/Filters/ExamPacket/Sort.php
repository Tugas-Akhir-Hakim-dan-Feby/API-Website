<?php

namespace App\Http\Filters\ExamPacket;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Sort
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('sort_direction')) {
            return $next($query);
        }

        $query->orderBy('id', request('sort_direction', 'asc'));

        return $next($query);
    }
}
