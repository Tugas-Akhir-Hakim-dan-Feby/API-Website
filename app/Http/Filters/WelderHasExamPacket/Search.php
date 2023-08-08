<?php

namespace App\Http\Filters\WelderHasExamPacket;

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
            $query->where('certificate_number', 'like', '%' . request('search') . '%');
            $query->orWhereHas('user', function ($query) {
                $query->where('name', 'like', '%' . request('search') . '%');
            });
        });

        $query->orderBy("validated_at", "asc");

        return $next($query);
    }
}
