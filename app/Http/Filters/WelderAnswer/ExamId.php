<?php

namespace App\Http\Filters\WelderAnswer;

use App\Models\Exam;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class ExamId
{
    public function handle(Builder $query, Closure $next)
    {
        if (!request()->has('exam_id')) {
            return $next($query);
        }

        $exam = Exam::where('uuid', request('exam_id'))->first();
        $query->where('exam_id', $exam->id);

        return $next($query);
    }
}
