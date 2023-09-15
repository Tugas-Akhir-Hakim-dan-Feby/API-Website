<?php

namespace App\Http\Controllers\Region;

use App\Http\Controllers\Controller;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\RegionFixer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProvinceController extends Controller
{
    use RegionFixer, MessageFixer;

    public function index()
    {
        try {
            $provinces = Http::get($this->apiRegion() . "/provinces.json");

            if (!$provinces->successful() || $provinces->status() != 200) {
                return null;
            }

            return $this->successMessage("data berhasil diambil", $provinces->json());
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function show($id)
    {
        try {
            $province = Http::get($this->apiRegion() . "/province/$id.json");

            if (!$province->successful() || $province->status() != 200) {
                return null;
            }

            return $this->successMessage("data berhasil diambil", $province->json());
        } catch (\Throwable $th) {
            return null;
        }
    }
}
