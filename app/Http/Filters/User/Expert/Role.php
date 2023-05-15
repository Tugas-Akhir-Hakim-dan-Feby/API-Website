<?php

namespace App\Http\Filters\User\Expert;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Role
{
    public function handle(Builder $query, Closure $next)
    {
        $query->with(['expert', 'welderMember.welderSkill']);

        return $next($query);
    }
}
