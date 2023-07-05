<?php

namespace App\Http\Filters\User\WelderMember;

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
            $query->where('name', 'like', "%" . request('search') . "%")
                ->orWhere('email', 'like', "%" . request('search') . "%")
                ->orWhere('membership_card', 'like', "%" . request('search') . "%")
                ->orWhereHas('welderMember', function ($query) {
                    $query->where('resident_id_card', 'like', "%" . request('search') . "%");
                });
        });

        return $next($query);
    }
}
