<?php

namespace App\Http\Filters\Advertisement;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByRecovery
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('recovery')) {
            return $next($query);
        }

        $query->onlyTrashed();

        return $next($query);
    }
}
