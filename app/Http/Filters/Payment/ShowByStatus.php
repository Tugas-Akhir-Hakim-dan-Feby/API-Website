<?php

namespace App\Http\Filters\Payment;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ShowByStatus
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('status')) {
            return $next($query);
        }

        $query->where('status', 'LIKE', '%' . request('status') . '%');

        return $next($query);
    }
}
