<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterJob extends Model
{
    use HasFactory;

    const ACCEPT = 1;

    const REJECT = 2;

    protected $fillable = ["user_id", "job_vacancy_id", "experience", "promote", "status", "police_record"];

    protected $hidden = ["user_id", "job_vacancy_id", "id"];

    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
    }

    public function jobVacancy()
    {
        return $this->hasOne(JobVacancy::class, "id", "job_vacancy_id");
    }
}
