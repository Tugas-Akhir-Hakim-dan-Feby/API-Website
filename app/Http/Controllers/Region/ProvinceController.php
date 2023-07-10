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
        $provinces = Http::get($this->apiRegion() . "/provinces.json");
        return $this->successMessage("data berhasil diambil", $provinces->json());
    }

    public function show($id)
    {
        $province = Http::get($this->apiRegion() . "/province/$id.json");
        return $this->successMessage("data berhasil diambil", $province->json());
    }
}
