<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $table = "user_operators";

    protected $fillable = [
        "user_id",
        "tuk_name",
        "tuk_type",
        "tuk_code",
        "address",
        "phone",
        "facsmile",
        "uuid",
    ];

    protected $hidden = [
        "id",
        "user_id",
    ];
}
