<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    const ACTIVE = 1;

    const INACTIVE = 0;

    protected $table = 'user_branches';

    protected $fillable = [
        'uuid',
        'user_id',
        'branch_id',
        'position',
        'phone',
        'address',
        'status',
        'gender',
        'birth_place',
        'date_birth',
        'nip',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'branch_id',
    ];
}
