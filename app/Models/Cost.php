<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    use HasFactory;

    const WELDER_MEMBER = 1;

    const COMPANY_MEMBER = 2;

    const ADVERTISEMENT = 3;

    const TEST_INSTITUTION = 4;

    protected $fillable = [
        'type_price',
        'nominal_price',
        'uuid'
    ];
}
