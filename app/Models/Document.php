<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['id', 'documentable_type', 'documentable_id'];

    public function documentable()
    {
        return $this->morphTo();
    }

    public function getDocumentPathAttribute($image)
    {
        if ($image && Storage::exists($image)) {
            return asset('storage/' . $image);
        }

        return asset('assets/images/image-not-found.png');
    }
}
