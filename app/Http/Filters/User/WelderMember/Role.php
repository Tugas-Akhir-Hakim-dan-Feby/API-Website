<?php

namespace App\Http\Filters\User\WelderMember;

use App\Models\Role as ModelsRole;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Role
{
    public function handle(Builder $query, Closure $next)
    {
        $query->with(['welderMember.welderSkill']);
        $query->whereHas('roles', function ($query) {
            $query->whereIn('id', [ModelsRole::MEMBER_WELDER]);
        });

        return $next($query);
    }
}
