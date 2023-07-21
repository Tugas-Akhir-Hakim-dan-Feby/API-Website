<?php

namespace App\Models\User;

use App\Models\JobVacancy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class CompanyMember extends Model
{
    use HasFactory;

    protected $table = 'user_company_members';

    protected $fillable = [
        'user_id',
        'company_name',
        'company_director',
        'company_address',
        'company_profile',
        'company_email',
        'company_legality',
        'company_logo',
        'phone',
        'facsmile',
        'status',
        'uuid',
    ];

    protected $hidden = [
        "id",
        "user_id",
    ];

    public function getCompanyLegalityAttribute($companyLegality)
    {
        if ($companyLegality && Storage::exists($companyLegality)) {
            return asset('storage/' . $companyLegality);
        }

        return asset('assets/images/image-not-found.png');
    }

    public function getCompanyLogoAttribute($companyLogo)
    {
        if ($companyLogo && Storage::exists($companyLogo)) {
            return asset('storage/' . $companyLogo);
        }

        return asset('assets/images/image-not-found.png');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function jobVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class, 'company_member_id', 'id');
    }
}
