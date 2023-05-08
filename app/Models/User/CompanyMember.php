<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyMember extends Model
{
    use HasFactory;

    protected $table = 'user_company_members';

    protected $fillable = [
        'user_id',
        'company_name',
        'company_director',
        'company_address',
        'company_profile',
        'company_email',
        'company_legality',
        'phone',
        'facsmile',
        'uuid',
    ];

    protected $hidden = [
        "id",
        "user_id",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
