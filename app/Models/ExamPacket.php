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
        "period",
        "practice_exam_address",
        "uuid",
    ];

    protected $hidden = [
        "id"
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function exam(): HasOne
    {
        return $this->hasOne(Exam::class, "exam_packet_id", "id");
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, "exam_packet_id", "id");
    }

    public function examPacketHasExperts(): HasMany
    {
        return $this->hasMany(ExpertHasExamPacket::class, 'exam_packet_id', 'id');
    }

    public function examPacketHasWelders(): HasMany
    {
        return $this->hasMany(WelderHasExamPacket::class, 'exam_packet_id', 'id');
    }

    public function examPacketHasWelder(): HasOne
    {
        return $this->hasOne(WelderHasExamPacket::class, 'exam_packet_id', 'id');
    }
}
