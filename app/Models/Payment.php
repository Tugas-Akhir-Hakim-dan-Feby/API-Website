<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'external_id',
        'status',
        'amount',
        'payment_link',
        'description',
        'uuid'
    ];

    protected $hidden = [
        'id',
        'user_id',
    ];
}
