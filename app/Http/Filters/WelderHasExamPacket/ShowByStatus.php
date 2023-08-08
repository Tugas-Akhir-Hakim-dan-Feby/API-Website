<?php

namespace App\Http\Filters\WelderHasExamPacket;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ShowByStatus
{
    public function handle(Builder $query, Closure $next)
    {
        $query->whereIn('status', [1, 3]);

        return $next($query);
    }
}
