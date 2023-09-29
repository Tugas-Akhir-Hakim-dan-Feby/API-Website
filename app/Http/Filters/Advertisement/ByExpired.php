<?php

namespace App\Http\Filters\Advertisement;

use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByExpired
{
    public function handle(Builder $query, Closure $next)
    {
        $dateNow = Carbon::today()->toDateString();

        $query->where('expired_at', ">=", $dateNow);

        return $next($query);
    }
}
