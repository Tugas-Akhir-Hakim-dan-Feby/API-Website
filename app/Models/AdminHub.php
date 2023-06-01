<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminHub extends Model
{
    use HasFactory;

    const ACTIVE = 1;

    const DEACTIVE = 1;

    protected $fillable = [
        'user_id',
        'position',
        'phone',
        'address',
        'status',
    ];
}
