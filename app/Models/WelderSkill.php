<?php

namespace App\Models;

use App\Models\User\WelderMember;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class WelderSkill extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'skill_name',
        'skill_description',
    ];

    protected $hidden = [
        'id',
    ];

    public function setUuidAttribute($uuid)
    {
        return $this->attributes['uuid'] = Str::uuid();
    }

    public function welderMember(): HasMany
    {
        return $this->hasMany(WelderMember::class, 'welder_skill_id', 'id');
    }
}
