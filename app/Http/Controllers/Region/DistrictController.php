<?php

namespace App\Http\Controllers\Region;

use App\Http\Controllers\Controller;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\RegionFixer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DistrictController extends Controller
{
    use RegionFixer, MessageFixer;

    public function index(Request $request)
    {
        $districts = Http::get($this->apiRegion() . "/districts/$request->regency_id.json");
        return $this->successMessage("data berhasil diambil", $districts->json());
    }

    public function show($id)
    {
        $district = Http::get($this->apiRegion() . "/district/$id.json");
        return $this->successMessage("data berhasil diambil", $district->json());
    }
}
