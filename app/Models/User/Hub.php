<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Hub extends Model
{
    use HasFactory;

    protected $table = 'user_hubs';

    protected $fillable = [
        'uuid',
        'position',
        'phone',
        'address',
        'status',
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
