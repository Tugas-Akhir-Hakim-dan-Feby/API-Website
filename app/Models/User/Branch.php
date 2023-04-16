<?php

namespace App\Models\User;

use App\Models\Branch as ModelsBranch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Branch extends Model
{
    use HasFactory;

    const ACTIVE = 1;

    const INACTIVE = 0;

    protected $table = 'user_branches';

    protected $fillable = [
        'uuid',
        'user_id',
        'branch_id',
        'position',
        'phone',
        'address',
        'status',
        'gender',
        'birth_place',
        'date_birth',
        'nip',
    ];

    protected $hidden = [
        'id',
        'user_id',
        'branch_id',
    ];

    public function branch(): HasOne
    {
        return $this->hasOne(ModelsBranch::class, 'id', 'branch_id');
    }
}
