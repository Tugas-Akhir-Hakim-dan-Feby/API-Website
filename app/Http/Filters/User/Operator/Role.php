<?php

namespace App\Http\Filters\User\Operator;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Role
{
    public function handle(Builder $query, Closure $next)
    {
        $query->with(['operator.logo']);
        $query->where('role_id', User::OPERATOR);

        return $next($query);
    }
}
