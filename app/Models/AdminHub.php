<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminHub extends Model
{
    use HasFactory;

    protected $fillable =[
        'hub_user_id',
        'hub_position',
        'hub_phone',
        'hub_address',
        'hub_status',
    ];
}
