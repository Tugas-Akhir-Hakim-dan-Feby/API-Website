<?php

namespace App\Models;

use App\Models\User\Operator;
use Illuminate\Database\Eloquent\Builder;
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
        "operator_id",
        "welder_skill_id",
        "year",
        "status",
        "exam_schedule",
        "close_schedule",
        "price",
        "start_time",
        "end_time",
        "period",
        "uuid",
    ];

    protected $hidden = [
        "id",
        "operator_id",
        "welder_skill_id",
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function operator(): HasOne
    {
        return $this->hasOne(Operator::class, "id", "operator_id");
    }

    public function exam(): HasOne
    {
        return $this->hasOne(Exam::class, "exam_packet_id", "id");
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, "exam_packet_id", "id");
    }

    public function competenceSchema(): HasOne
    {
        return $this->hasOne(WelderSkill::class, 'id', 'welder_skill_id');
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
