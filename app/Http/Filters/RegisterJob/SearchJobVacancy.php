<?php

namespace App\Http\Filters\RegisterJob;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class SearchJobVacancy
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request("job_vacancy")) {
            return $next($query);
        }

        $query->whereHas("jobVacancy", function ($query) {
            $query->where("uuid", request('job_vacancy'));
        });

        return $next($query);
    }
}
