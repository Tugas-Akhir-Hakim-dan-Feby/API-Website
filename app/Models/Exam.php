<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Exam extends Model
{
    use HasFactory;

    const TYPE_MULTIPLECHOICE = "MultipleChoice";

    const TYPE_TRUEFALSE = "TrueFalse";

    protected $fillable = [
        "answer_id",
        "exam_packet_id",
        "question",
        "uuid",
        "type"
    ];

    protected $hidden = [
        "id",
        "answer_id",
        "exam_packet_id",
    ];

    public function welderAnswer(): HasOne
    {
        return $this->hasOne(WelderAnswer::class, "exam_id", "id");
    }

    public function correctAnswer(): HasOne
    {
        return $this->hasOne(Answer::class, 'id', 'answer_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, "exam_id", "id");
    }
}
