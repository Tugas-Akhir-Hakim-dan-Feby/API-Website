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

        if (!$user->onlyRoles([User::ADMIN_APP, User::ADMIN_HUB])) {
            $query->where('user_id', $user->id);
        }

        return $next($query);
    }
}
