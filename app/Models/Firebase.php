<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Firebase extends Model
{
    use HasFactory;

    const EXAMS = "exams";
    const ANSWERS = "answers";
    const WELDER_ANSWER = "welderAnswers";
}
