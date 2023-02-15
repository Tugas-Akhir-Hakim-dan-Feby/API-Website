<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const ADMIN_APP = 1;

    const ADMIN_PUSAT = 2;

    const ADMIN_CABANG = 3;

    const PAKAR = 4;

    const MEMBER_COMPANY = 5;

    const MEMBER_WELDER = 6;

    const GUEST = 7;

    protected $fillable = [
        'name',
        'guard_name',
    ];
}
