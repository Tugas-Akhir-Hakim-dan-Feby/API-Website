<?php

namespace App\Models\User;

use App\Models\WelderDocument;
use App\Models\WelderSkill;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class WelderMember extends Model
{
    use HasFactory;

    const ACTIVE = 1;

    const INACTIVE = 0;

    protected $table = 'user_welder_members';

    protected $fillable = [
        'user_id',
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
    ];

    public function getCertificateSchoolAttribute($image)
    {
        if ($image && Storage::exists($image)) {
            return asset('storage/' . $image);
        }

        return asset('assets/images/image-not-found.png');
    }

    public function getPasPhotoAttribute($image)
    {
        if ($image && Storage::exists($image)) {
            return asset('storage/' . $image);
        }

        return asset('assets/images/profile-not-found.webp');
    }
}
