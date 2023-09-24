<?php

namespace App\Http\Filters\ExamPacket;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ShowByCompetence
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('competence_id')) {
            return $next($query);
        }

        $query->whereHas('competenceSchema', function ($query) {
            if (request('competence_id') != "0") {
                $query->where('uuid', request('competence_id'));
            }
        });

        return $next($query);
    }
}
