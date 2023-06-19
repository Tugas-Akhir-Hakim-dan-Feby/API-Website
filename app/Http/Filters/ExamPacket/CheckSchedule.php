<?php

namespace App\Http\Filters\ExamPacket;

use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class CheckSchedule
{
    public function handle(Builder $query, Closure $next)
    {
        $dateNow = Carbon::today()->toDateString();

        if (auth()->user()->isMemberWelder()) {
            $query->where("schedule", ">=", $dateNow);
        }

        return $next($query);
    }
}
