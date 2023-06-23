<?php

namespace App\Http\Filters\Payment;

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
            $query->where('description', 'like', '%' . request('search') . '%')
                ->orWhere('external_id', 'like', '%' . request('search') . '%');

            $query->orWhereHas('user', function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%')
                    ->orWhere('email', 'like', '%' . request('search') . '%');
            });
        });

        return $next($query);
    }
}
