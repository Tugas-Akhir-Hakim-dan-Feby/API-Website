<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Notifications\SendEmailVerification;
use App\Notifications\SendResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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

    public function adminHub(): HasOne
    {
        return $this->hasOne(AdminHub::class, 'user_id', 'id');
    }

    public function branchadmin(): HasOne
    {
        return $this->hasOne(BranchAdmin::class, 'user_id', 'id');
    }
}
