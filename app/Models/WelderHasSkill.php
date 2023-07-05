<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WelderHasSkill extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "welder_skill_id"];

    protected $hidden = ["user_id", "welder_skill_id"];

    public $timestamps = false;

    public function welderSkill(): HasOne
    {
        return $this->hasOne(WelderSkill::class, 'id', 'welder_skill_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
