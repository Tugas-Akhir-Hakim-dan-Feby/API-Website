<?php

namespace App\Http\Filters\User\Expert;

use App\Models\User;
use App\Models\User\Expert;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Approved
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('approved')) {
            return $next($query);
        }

        if (request('approved') == "false") {
            $query->whereHas('expert', function ($query) {
                $query->where('status', Expert::NOT_APPROVED);
            });
        } else {
            $query->whereHas('expert', function ($query) {
                $query->where('status', Expert::APPROVED);
            });
        }

        return $next($query);
    }
}
