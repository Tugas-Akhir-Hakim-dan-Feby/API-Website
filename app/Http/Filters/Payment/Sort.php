<?php

namespace App\Http\Filters\Payment;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Sort
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('sort_direction')) {
            return $next($query);
        }

        $query->orderBy('created_at', request('sort_direction', 'asc'));

        return $next($query);
    }
}
