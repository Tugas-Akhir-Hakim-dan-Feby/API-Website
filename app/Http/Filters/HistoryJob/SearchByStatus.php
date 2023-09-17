<?php

namespace App\Http\Filters\HistoryJob;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class SearchByStatus
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('status')) {
            return $next($query);
        }
        $query->where('status', request('status'));

        return $next($query);
    }
}
