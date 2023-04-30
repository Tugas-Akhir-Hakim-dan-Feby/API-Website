<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const ADMIN_APP = 1;

    const ADMIN_HUB = 2;

    const ADMIN_BRANCH = 3;

    const EXPERT = 4;

    const MEMBER_COMPANY = 5;

    const MEMBER_WELDER = 6;

    const GUEST = 7;

    protected $fillable = [
        'name',
        'guard_name',
    ];
}
