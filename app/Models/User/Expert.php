<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Expert extends Model
{
    use HasFactory;

    const ACTIVE = 1;

    const INACTIVE = 0;

    const APPROVED = "APPROVED";

    const NOT_APPROVED = "NOT-APPROVED";

    protected $table = "user_experts";

    protected $fillable = [
        "user_id",
        "instance",
        "certificate_competency",
        "certificate_profession",
        "working_mail",
        "career",
        "uuid",
    ];

    protected $hidden = [
        "user_id",
        "id",
    ];

    public function getCertificateCompetencyAttribute($image)
    {
        return asset('storage/' . $image);
    }

    public function getCertificateProfessionAttribute($image)
    {
        return asset('storage/' . $image);
    }

    public function getWorkingMailAttribute($image)
    {
        return asset('storage/' . $image);
    }

    public function getCareerAttribute($image)
    {
        return asset('storage/' . $image);
    }

    public function welderMember(): HasOne
    {
        return $this->hasOne(WelderMember::class, 'user_id', 'user_id');
    }
}
