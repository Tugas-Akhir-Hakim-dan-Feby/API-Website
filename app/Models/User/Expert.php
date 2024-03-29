<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

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
        "status",
        "uuid",
    ];

    protected $hidden = [
        "user_id",
        "id",
    ];

    public function getCertificateCompetencyAttribute($image)
    {
        if ($image && Storage::exists($image)) {
            return asset('storage/' . $image);
        }

        return null;
    }

    public function getCertificateProfessionAttribute($image)
    {
        if ($image && Storage::exists($image)) {
            return asset('storage/' . $image);
        }

        return null;
    }

    public function getWorkingMailAttribute($image)
    {
        if ($image && Storage::exists($image)) {
            return asset('storage/' . $image);
        }

        return null;
    }

    public function getCareerAttribute($image)
    {
        if ($image && Storage::exists($image)) {
            return asset('storage/' . $image);
        }

        return null;
    }

    public function welderMember(): HasOne
    {
        return $this->hasOne(WelderMember::class, 'user_id', 'user_id');
    }
}
