<?php

namespace App\Models;

use App\Models\User\CompanyMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        "pamphlet",
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

    public function getPamphletAttribute($image)
    {
        if ($image) {
            return asset('storage/' . $image);
        }

        return asset('assets/images/image-not-found.png');
    }

    public function welderSkill(): HasOne
    {
        return $this->hasOne(WelderSkill::class, 'id', 'welder_skill_id');
    }

    public function companyMember(): HasOne
    {
        return $this->hasOne(CompanyMember::class, 'id', 'company_member_id');
    }
}
