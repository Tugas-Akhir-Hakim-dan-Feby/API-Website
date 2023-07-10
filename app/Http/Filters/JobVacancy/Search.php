<?php

namespace App\Http\Filters\JobVacancy;

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
            $query->where('placement', 'LIKE', '%' . request('search') . '%');
            $query->orWhere('work_type', 'LIKE', '%' . request('search') . '%');
            $query->orWhereHas('welderSkill', function ($query) {
                $query->where('skill_name', 'LIKE', '%' . request('search') . '%');
            });
            $query->orWhereHas('companyMember', function ($query) {
                $query->where('company_name', 'LIKE', '%' . request('search') . '%');
            });
        });

        return $next($query);
    }
}
