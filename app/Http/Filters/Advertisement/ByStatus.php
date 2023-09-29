<?php

namespace App\Http\Filters\Advertisement;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByStatus
{
    public function handle(Builder $query, Closure $next)
    {
        $query->where('is_active', request('status', 1));

        return $next($query);
    }
}
