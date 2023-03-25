<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelderMember extends Model
{
    use HasFactory;

    const ACTIVE = 1;

    const INACTIVE = 0;

    protected $table = 'user_welder_members';

    protected $fillable = [
        'user_id',
        'welder_skill_id',
        'resident_id_card',
        'date_birth',
        'birth_place',
        'working_status',
        'status',
        'uuid',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'welder_skill_id',
    ];
}
