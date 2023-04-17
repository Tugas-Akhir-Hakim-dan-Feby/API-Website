<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        "answer_id",
        "exam_packet_id",
        "question",
        "uuid"
    ];

    protected $hidden = [
        "id",
        "answer_id",
        "exam_packet_id",
    ];

    public function correctAnswer(): HasOne
    {
        return $this->hasOne(Answer::class, "id", "answer_id");
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, "exam_id", "id");
    }
}
