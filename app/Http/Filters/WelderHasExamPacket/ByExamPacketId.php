<?php

namespace App\Http\Filters\WelderHasExamPacket;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByExamPacketId
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('exam_packet_id')) {
            return $next($query);
        }

        $query->whereHas('examPacket', function ($query) {
            $query->where('uuid', request('exam_packet_id'));
        });

        return $next($query);
    }
}
