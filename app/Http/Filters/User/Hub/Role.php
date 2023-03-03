<?php

namespace App\Http\Filters\User\Hub;

use App\Models\Role as ModelsRole;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Role
{
    public function handle(Builder $query, Closure $next)
    {
        $query->with('adminHub');
        $query->where('role_id', ModelsRole::ADMIN_PUSAT);

        return $next($query);
    }
}
