<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Hub extends Model
{
    use HasFactory;

    const ACTIVE = 1;

    const INACTIVE = 0;

    protected $table = 'user_hubs';

    protected $fillable = [
        'uuid',
        'position',
        'phone',
        'address',
        'status',
        'user_id',
        'gender',
        'birth_place',
        'date_birth',
        'nip',
    ];

    protected $hidden = [
        'id',
        'user_id',
    ];

    public function setUuidAttribute($uuid)
    {
        return $this->attributes['uuid'] = Str::uuid();
    }
}
