<?php

namespace App\Http\Filters\JobVacancy;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class SearchRegion
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request('region')) {
            return $next($query);
        }

        $query->where('placement', 'LIKE', '%' . request('region') . '%');

        return $next($query);
    }
}
