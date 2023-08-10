<?php

namespace App\Models\User;

use App\Models\Document;
use App\Models\ExamPacket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Operator extends Model
{
    use HasFactory;

    protected $table = "user_operators";

    protected $fillable = [
        "user_id",
        "tuk_name",
        "tuk_type",
        "tuk_code",
        "address",
        "phone",
        "facsmile",
        "uuid",
    ];

    protected $hidden = [
        "id",
        "user_id",
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function logo(): MorphOne
    {
        return $this->morphOne(Document::class, 'documentable');
    }

    public function examPackets(): HasMany
    {
        return $this->hasMany(ExamPacket::class, "operator_id", "id");
    }
}
