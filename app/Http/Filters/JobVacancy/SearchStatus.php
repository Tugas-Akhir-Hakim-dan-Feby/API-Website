<?php

namespace App\Http\Filters\JobVacancy;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class SearchStatus
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request('status')) {
            return $next($query);
        }

        if (request('status') == "3") {
            $query->whereHas("registerJobs", function ($query) {
                $query->where("user_id", auth()->user()->id);
            });
        } else {
            $query->whereHas("registerJobs", function ($query) {
                $query->where('status', request('status'))->where("user_id", auth()->user()->id);
            });
        }

        return $next($query);
    }
}
