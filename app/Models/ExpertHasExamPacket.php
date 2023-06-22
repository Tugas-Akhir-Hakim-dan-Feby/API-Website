<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertHasExamPacket extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "exam_packet_id",
        "uuid",
    ];

    protected $hidden = [
        "id",
        "user_id",
        "exam_packet_id",
    ];

    public function examPacket()
    {
        return $this->hasOne(ExamPacket::class, 'id', 'exam_packet_id');
    }
}
