<?php

namespace App\Http\Filters\ExamPacket;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ShowByOperator
{
    public function handle(Builder $query, Closure $next)
    {
        $operator = User::where('id', auth()->user()->id)->whereHas("roles", function ($query) {
            $query->where("id", User::OPERATOR);
        })->first();

        if ($operator) {
            $query->where("operator_id", $operator->operator->id);
        }

        return $next($query);
    }
}
