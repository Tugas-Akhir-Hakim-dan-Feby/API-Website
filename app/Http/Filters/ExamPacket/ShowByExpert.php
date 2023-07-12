<?php

namespace App\Http\Filters\ExamPacket;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ShowByExpert
{
    public function handle(Builder $query, Closure $next)
    {
        $experUser = User::where('id', auth()->user()->id)->whereHas("roles", function ($query) {
            $query->where("id", User::EXPERT);
        })->first();

        if ($experUser) {
            $query->whereHas("examPacketHasExperts", function ($query) {
                $query->where("user_id", auth()->user()->id);
            });
        }

        return $next($query);
    }
}
