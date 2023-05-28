<?php

namespace App\Http\Filters\Exam;

use App\Models\ExamPacket;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ByExamPacketId
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('exam_packet_id')) {
            return $next($query);
        }

        $query->with(['answers']);

        $examPacket = ExamPacket::where('uuid', request('exam_packet_id'))->first();

        $query->where('exam_packet_id', $examPacket->id);

        return $next($query);
    }
}
