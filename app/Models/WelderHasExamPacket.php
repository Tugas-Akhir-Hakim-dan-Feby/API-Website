<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelderHasExamPacket extends Model
{
    use HasFactory;

    const FINISH = 1;

    const PUNISHMENT = 2;

    protected $fillable = [
        "user_id",
        "exam_packet_id",
        "penalty",
        "status",
        "key_packet",
        "practice_value",
        "uuid",
    ];

    protected $hidden = [
        "id",
        "user_id",
        "key_packet",
        "exam_packet_id",
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function examPacket()
    {
        return $this->hasOne(ExamPacket::class, 'id', 'exam_packet_id');
    }


    public function scopeWelderAuth(Builder $query)
    {
        $query->where("user_id", auth()->user()->id);
    }
}
