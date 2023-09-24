<?php

namespace App\Http\Filters\Advertisement;

use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByAuth
{
    public function handle(Builder $query, Closure $next)
    {
        $user = User::find(auth()->user()->id);

        if ($user->isAdminApp()) {
            return $next($query);
        }

        $query->where('user_id', $user->id);

        return $next($query);
    }
}
