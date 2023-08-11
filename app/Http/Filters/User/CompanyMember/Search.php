<?php

namespace App\Http\Filters\User\CompanyMember;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class Search
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request('search')) {
            return $next($query);
        }
        $query->where(function ($query) {
            $query->where('name', 'like', "%" . request('search') . "%")
                ->orWhere('email', 'like', "%" . request('search') . "%")
                ->orWhereHas('companyMember', function ($query) {
                    $query->where('company_name', 'like', "%" . request('search') . "%");
                });
        });

        return $next($query);
    }
}
