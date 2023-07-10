<?php

namespace App\Http\Filters\JobVacancy;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class SearchSpecificSkill
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request('skill')) {
            return $next($query);
        }

        $query->whereHas("welderSkill", function ($query) {
            $query->where('uuid', request('skill'));
        });

        return $next($query);
    }
}
