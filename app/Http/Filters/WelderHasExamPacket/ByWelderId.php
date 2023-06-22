<?php

namespace App\Http\Filters\WelderHasExamPacket;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByWelderId
{
    public function handle(Builder $query, Closure $next)
    {
        $user = User::where('id', auth()->user()->id)->whereHas("roles", function ($query) {
            $query->whereIn("id", [User::MEMBER_WELDER, User::GUEST]);
        })->first();

        if ($user) {
            $query->where("user_id", $user->id);
        }

        return $next($query);
    }
}
