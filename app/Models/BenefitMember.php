<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BenefitMember extends Model
{
    use HasFactory;

    protected $fillable = ["cost_id", "description"];
}
