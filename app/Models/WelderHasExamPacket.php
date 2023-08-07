<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
        "payment",
        "key_packet",
        "grade",
        "notes",
        "certificate_number",
        "uuid",
        "validated_at",
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

    public function getPaymentAttribute($image)
    {
        if ($image && Storage::exists($image)) {
            return asset('storage/' . $image);
        }

        return null;
    }

    public function scopeWelderAuth(Builder $query)
    {
        $query->where("user_id", auth()->user()->id);
    }
}
