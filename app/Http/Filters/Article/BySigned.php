<?php

namespace App\Http\Filters\Article;

use App\Models\Role;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class BySigned
{
    public function handle(Builder $query, Closure $next)
    {
        $user = Auth::user();

        if ($user->isAdminBranch()) {
            $query->whereHas("user", function ($query) {
                $query->where("role_id", Role::ADMIN_CABANG);
            });
        }

        if ($user->isMemberCompany()) {
            $query->whereHas("user", function ($query) {
                $query->where("role_id", Role::MEMBER_COMPANY);
            });
        }

        return $next($query);
    }
}
