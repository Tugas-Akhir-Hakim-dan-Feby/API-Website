<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    const PAID = "PAID";

    const PENDING = "PENDING";

    protected $fillable = [
        'user_id',
        'external_id',
        'status',
        'amount',
        'payment_link',
        'description',
        'recreated_at',
        'uuid'
    ];

    protected $hidden = [
        'id',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class, 'external_id', 'external_id');
    }

    public function isRecreated()
    {
        return $this->recreated_at !== null;
    }

    public function markAsRecreated()
    {
        $this->update(['recreated_at' => now()]);
    }
}
