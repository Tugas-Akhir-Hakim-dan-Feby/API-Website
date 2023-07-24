<?php

namespace App\Models;

use App\Http\Traits\RegionFixer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class PersonalData extends Model
{
    use HasFactory, RegionFixer;

    protected $fillable = ["user_id", "village", "district", "regency", "province", "zip_code", "phone", "citizenship", "curriculum_vitae"];

    protected $hidden = ["user_id", "id"];

    public function getCurriculumVitaeAttribute($document)
    {
        if ($document && Storage::exists($document)) {
            return asset('storage/' . $document);
        }

        return null;
    }

    public function getProvinceAttribute()
    {
        $apiProvince = Http::get($this->apiRegion() . "/province/" . $this->attributes['province'] . ".json")->json();

        return $apiProvince;
    }

    public function getRegencyAttribute()
    {
        $apiRegency = Http::get($this->apiRegion() . "/regency/" . $this->attributes['regency'] . ".json")->json();

        return $apiRegency;
    }

    public function getDistrictAttribute()
    {
        $apiDistrict = Http::get($this->apiRegion() . "/district/" . $this->attributes['district'] . ".json")->json();

        return $apiDistrict;
    }

    public function getVillageAttribute()
    {
        $apiVillage = Http::get($this->apiRegion() . "/village/" . $this->attributes['village'] . ".json")->json();

        return $apiVillage;
    }
}
