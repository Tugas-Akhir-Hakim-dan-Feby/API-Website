<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelderHasExamPacket extends Model
{
    use HasFactory;

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
}
