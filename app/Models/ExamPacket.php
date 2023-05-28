<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ExamPacket extends Model
{
    use HasFactory;

    const ACTIVE = 1;

    const INACTIVE = 0;

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

    public function exam(): HasOne
    {
        return $this->hasOne(Exam::class, "exam_packet_id", "id");
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, "exam_packet_id", "id");
    }
}
