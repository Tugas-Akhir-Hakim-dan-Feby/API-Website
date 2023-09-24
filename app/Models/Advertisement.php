<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advertisement extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE = 1;

    const INACTIVE = 0;

    const DELETED = 2;

    protected $fillable = [
        "uuid",
        "cost",
        "user_id",
        "is_active",
        "expired_at",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function document(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable');
    }
}
