<?php

namespace App\Http\Controllers\Region;

use App\Http\Controllers\Controller;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\RegionFixer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RegencyController extends Controller
{
    use RegionFixer, MessageFixer;

    public function index(Request $request)
    {
        $regencies = Http::get($this->apiRegion() . "/regencies/$request->province_id.json");
        return $this->successMessage("data berhasil diambil", $regencies->json());
    }

    public function show($id)
    {
        $regency = Http::get($this->apiRegion() . "/regency/$id.json");
        return $this->successMessage("data berhasil diambil", $regency->json());
    }
}
