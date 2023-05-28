<?php

namespace App\Http\Filters\User\CompanyMember;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Role
{
    public function handle(Builder $query, Closure $next)
    {
        $query->with(['companyMember', 'document']);
        $query->whereHas('roles', function ($query) {
            $query->whereIn('id', [User::MEMBER_COMPANY]);
        });

        return $next($query);
    }
}
