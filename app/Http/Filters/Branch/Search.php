<?php

namespace App\Http\Filters\Branch;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Search
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('search')) {
            return $next($query);
        }

        $query->where(function ($query) {
            $query->where('branch_name', 'LIKE', '%' . request('search') . '%')
                ->orWhere('branch_address', 'LIKE', '%' . request('search') . '%')
                ->orWhere('branch_phone', 'LIKE', '%' . request('search') . '%');
        });

        return $next($query);
    }
}
