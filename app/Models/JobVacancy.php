<?php

namespace App\Models;

use App\Models\User\CompanyMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobVacancy extends Model
{
    use HasFactory;

    const ACTIVE = 1;

    protected $fillable = [
        "company_member_id",
        "welder_skill_id",
        "description",
        "placement",
        "work_type",
        "contact",
        "contact_name",
        "deadline",
        "salary",
        "status",
        "slug",
        "uuid",
    ];

    protected $hidden = [
        "company_member_id",
        "welder_skill_id",
    ];

    public function welderSkill(): HasOne
    {
        return $this->hasOne(WelderSkill::class, 'id', 'welder_skill_id');
    }

    public function companyMember(): HasOne
    {
        return $this->hasOne(CompanyMember::class, 'id', 'company_member_id');
    }

    public function registerJobs(): HasMany
    {
        return $this->hasMany(RegisterJob::class, 'job_vacancy_id', 'id');
    }
}
