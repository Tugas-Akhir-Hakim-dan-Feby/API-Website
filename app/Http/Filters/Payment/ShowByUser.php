<?php

namespace App\Http\Filters\Payment;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ShowByUser
{
    public function handle(Builder $query, Closure $next)
    {
        $user = User::find(auth()->user()->id);

        if ($user->onlyRoles([User::MEMBER_WELDER, User::MEMBER_COMPANY, User::GUEST, User::PAKAR])) {
            $query->where('user_id', $user->id);
        }

        return $next($query);
    }
}
