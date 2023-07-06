<?php

namespace App\Http\Filters\JobVacancy;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class LoggedIn
{
    public function handle(Builder $query, Closure $next)
    {
        $user = User::where('id', auth()->user()->id)->whereHas('roles', function ($query) {
            $query->where('id', User::MEMBER_COMPANY);
        })->first();

        if (!$user) {
            return $next($query);
        }

        $query->where('company_member_id', $user->companyMember->id);

        return $next($query);
    }
}
