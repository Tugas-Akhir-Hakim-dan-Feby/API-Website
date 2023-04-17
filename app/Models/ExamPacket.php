<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamPacket extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "year",
        "status",
        "schedule",
        "start_time",
        "end_time",
        "period",
        "uuid",
    ];

    protected $hidden = [
        "id"
    ];

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, "exam_packet_id", "id");
    }
}
