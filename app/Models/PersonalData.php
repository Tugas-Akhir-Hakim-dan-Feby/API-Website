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
        try {
            $apiProvince = Http::get($this->apiRegion() . "/province/" . $this->attributes['province'] . ".json");

            if (!$apiProvince->successful() || $apiProvince->status() != 200) {
                return null;
            }

            return $apiProvince->json();
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getRegencyAttribute()
    {
        try {
            $apiRegency = Http::get($this->apiRegion() . "/regency/" . $this->attributes['regency'] . ".json");

            if (!$apiRegency->successful() || $apiRegency->status() != 200) {
                return null;
            }

            return $apiRegency->json();
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getDistrictAttribute()
    {
        try {
            $apiDistrict = Http::get($this->apiRegion() . "/district/" . $this->attributes['district'] . ".json");

            if (!$apiDistrict->successful() || $apiDistrict->status() != 200) {
                return null;
            }

            return $apiDistrict->json();
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getVillageAttribute()
    {
        try {
            $apiVillage = Http::get($this->apiRegion() . "/village/" . $this->attributes['village'] . ".json");

            if (!$apiVillage->successful() || $apiVillage->status() != 200) {
                return null;
            }

            return $apiVillage->json();
        } catch (\Throwable $th) {
            return null;
        }
    }
}
