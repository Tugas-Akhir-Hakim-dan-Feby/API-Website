<?php

namespace App\Http\Filters\User\Expert;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Role
{
    public function handle(Builder $query, Closure $next)
    {
        $query->with(['expert', 'welderHasSkills.welderSkill', 'welderMember']);

        $query->where('role_id', User::EXPERT);

        return $next($query);
    }
}
