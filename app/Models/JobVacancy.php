<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobVacancy extends Model
{
    use HasFactory;

    protected $fillable = [
        "company_member_id",
        "welder_skill_id",
        "description",
        "placement",
        "pamphlet",
        "deadline",
        "salary",
        "status",
        "uuid",
    ];

    public function welderSkill(): HasOne
    {
        return $this->hasOne(WelderSkill::class, 'id', 'welder_skill_id');
    }

    public function companyMember(): HasOne
    {
        return $this->hasOne(companyMember::class, 'id', 'company_member_id');
    }
}
