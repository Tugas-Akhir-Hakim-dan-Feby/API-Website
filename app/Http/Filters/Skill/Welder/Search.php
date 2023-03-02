<?php

namespace App\Http\Filters\Skill\Welder;

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
            $query->where('skill_name', 'like', '%' . request()->search . '%')
                ->orWhere('skill_description', 'like', '%' . request()->search . '%');
        });

        return $next($query);
    }
}
