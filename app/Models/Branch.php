<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'branch_name',
        'branch_address',
        'branch_phone',
        'branch_structure',
    ];

    protected $hidden = [
        'id',
    ];
}
