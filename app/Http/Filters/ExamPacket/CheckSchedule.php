<?php

namespace App\Http\Filters\ExamPacket;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class CheckSchedule
{
    public function handle(Builder $query, Closure $next)
    {
        $dateNow = Carbon::today()->toDateString();

        $user = User::findOrFail(auth()->user()->id);

        if ($user->onlyRoles([User::MEMBER_INDIVIDUAL, User::MEMBER_APPLICATION])) {
            $query->where("close_schedule", ">=", $dateNow);
        }

        return $next($query);
    }
}
