<?php

namespace App\Http\Filters\User\Operator;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Search
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request('search')) {
            return $next($query);
        }

        $query->where(function (Builder $query) {
            $query->where('email', 'LIKE', '%' . request('search') . '%');
            $query->orWhereHas('operator', function (Builder $query) {
                $query->where('tuk_name', 'LIKE', '%' . request('search') . '%');
                $query->orWhere('tuk_code', 'LIKE', '%' . request('search') . '%');
                $query->orWhere('address', 'LIKE', '%' . request('search') . '%');
            });
        });

        return $next($query);
    }
}
