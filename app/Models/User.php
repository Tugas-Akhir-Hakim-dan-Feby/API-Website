<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Traits\CheckRoles;
use App\Models\User\Branch;
use App\Models\User\CompanyMember;
use App\Models\User\Expert;
use App\Models\User\Hub;
use App\Models\User\WelderMember;
use App\Notifications\SendEmailVerification;
use App\Notifications\SendResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, CheckRoles;

    const ADMIN_APP = 1;

    const ADMIN_PUSAT = 2;

    const ADMIN_CABANG = 3;

    const PAKAR = 4;

    const MEMBER_COMPANY = 5;

    const MEMBER_WELDER = 6;

    const GUEST = 7;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'uuid',
        'role_id',
        'email_verified_at',
        'remember_token',
        'membership_card'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sendPasswordResetNotification($token)
    {
        $url = url("/auth/new-password?token=$token&email=$this->email");

        $this->notify(new SendResetPassword($url));
    }

    public function sendActivationUserNotification($token)
    {
        $url = url("/auth/new-password?token=$token&email=$this->email");

        $this->notify(new SendEmailVerification($url));
    }

    public function setUuidAttribute($uuid)
    {
        return $this->attributes['uuid'] = Str::uuid();
    }

    public function document()
    {
        return $this->morphOne(Document::class, 'documentable');
    }

    public function welderDocuments(): MorphMany
    {
        return $this->morphMany(WelderDocument::class, "documentable");
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'user_id', 'id');
    }

    public function adminHub(): HasOne
    {
        return $this->hasOne(Hub::class, 'user_id', 'id');
    }

    public function adminBranch(): HasOne
    {
        return $this->hasOne(Branch::class, 'user_id', 'id');
    }

    public function expert(): HasOne
    {
        return $this->hasOne(Expert::class, 'user_id', 'id');
    }

    public function welderHasSkills(): HasMany
    {
        return $this->hasMany(WelderHasSkill::class, 'user_id', 'id');
    }

    public function welderMember(): HasOne
    {
        return $this->hasOne(WelderMember::class, 'user_id', 'id');
    }

    public function companyMember(): HasOne
    {
        return $this->hasOne(CompanyMember::class, 'user_id', 'id');
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'user_id', 'id');
    }

    public function personalData(): HasOne
    {
        return $this->hasOne(PersonalData::class, 'user_id', 'id');
    }

    public function detailWorker(): HasOne
    {
        return $this->hasOne(WelderMember::class, 'user_id', 'id');
    }

    public function registerJob(): HasOne
    {
        return $this->hasOne(RegisterJob::class, 'user_id', 'id');
    }
}
