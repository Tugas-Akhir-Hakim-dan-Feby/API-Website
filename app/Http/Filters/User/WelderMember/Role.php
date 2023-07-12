<?php

namespace App\Http\Filters\User\WelderMember;

use App\Models\Role as ModelsRole;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Role
{
    public function handle(Builder $query, Closure $next)
    {
        $query->with(['welderMember', 'welderHasSkills.welderSkill']);
        $query->whereHas('roles', function ($query) {
            $query->whereIn('id', [User::MEMBER_INDIVIDUAL]);
        });

        return $next($query);
    }
}
