<?php

namespace App\Models\User;

use App\Models\WelderDocument;
use App\Models\WelderSkill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class WelderMember extends Model
{
    use HasFactory;

    const ACTIVE = 1;

    const INACTIVE = 0;

    protected $table = 'user_welder_members';

    protected $fillable = [
        'user_id',
        'welder_skill_id',
        'resident_id_card',
        'date_birth',
        'birth_place',
        'working_status',
        'status',
        "pas_photo",
        'uuid',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'welder_skill_id',
    ];

    public function welderSkill(): HasOne
    {
        return $this->hasOne(WelderSkill::class, 'id', 'welder_skill_id');
    }

    public function getCertificateSchoolAttribute($image)
    {
        return asset('storage/' . $image);
    }

    public function getPasPhotoAttribute($image)
    {
        return asset('storage/' . $image);
    }
}
