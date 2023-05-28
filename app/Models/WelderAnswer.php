<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelderAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'answer_id',
        'user_id',
        'exam_id',
    ];

    protected $hidden = [
        'id',
        'answer_id',
        'user_id',
        'exam_id',
    ];

    public function answer()
    {
        return $this->hasOne(Answer::class, 'id', 'answer_id');
    }
}
