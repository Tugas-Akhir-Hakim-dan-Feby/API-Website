<?php

namespace App\Http\Filters\User\Branch;

use App\Models\Role as ModelsRole;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Role
{
    public function handle(Builder $query, Closure $next)
    {
        $query->with(['adminBranch.branch', 'document']);
        $query->where('role_id', ModelsRole::ADMIN_CABANG);

        return $next($query);
    }
}
