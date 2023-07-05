<?php

namespace App\Http\Filters\User\WelderMember;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class WelderSkillId
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('welder_skill_id')) {
            return $next($query);
        }

        if (!request('welder_skill_id')) {
            return $next($query);
        }

        $query->whereHas("welderHasSkills.welderSkill", function ($query) {
            $query->where('uuid', request('welder_skill_id'));
        });

        return $next($query);
    }
}
