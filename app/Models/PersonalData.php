<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalData extends Model
{
    use HasFactory;

    protected $fillable = ["user_id", "village", "district", "regency", "province", "zip_code", "phone", "citizenship", "curriculum_vitae"];

    protected $hidden = ["user_id", "id"];

    public function getCurriculumVitaeAttribute($document)
    {
        return asset('storage/' . $document);
    }
}
