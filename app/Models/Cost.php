<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function benefits(): HasMany
    {
        return $this->hasMany(BenefitMember::class, 'cost_id', 'id');
    }
}
