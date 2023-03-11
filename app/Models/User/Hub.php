<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hub extends Model
{
    use HasFactory;

    protected $table = 'user_hubs';

    protected $fillable = [
        'user_id',
        'position',
        'phone',
        'address',
        'status',
    ];
}
