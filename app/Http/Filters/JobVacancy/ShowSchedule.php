<?php

namespace App\Http\Filters\JobVacancy;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ShowSchedule
{
    public function handle(Builder $query, Closure $next)
    {
        $user = auth()->user();

        if (!$user->onlyRoles([User::ADMIN_APP, User::ADMIN_HUB, User::ADMIN_BRANCH, User::MEMBER_COMPANY])) {
            $query->where('deadline', ">=", Carbon::today()->toDateTimeString());
        }

        return $next($query);
    }
}
