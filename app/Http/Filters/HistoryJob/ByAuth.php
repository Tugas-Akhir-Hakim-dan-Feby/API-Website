<?php

namespace App\Http\Filters\HistoryJob;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByAuth
{
    public function handle(Builder $query, Closure $next)
    {
        $query->where('user_id', auth()->user()->id);

        return $next($query);
    }
}
