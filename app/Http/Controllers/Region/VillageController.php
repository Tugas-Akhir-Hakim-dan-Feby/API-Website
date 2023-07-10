<?php

namespace App\Http\Controllers\Region;

use App\Http\Controllers\Controller;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\RegionFixer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VillageController extends Controller
{
    use RegionFixer, MessageFixer;

    public function index(Request $request)
    {
        $villages = Http::get($this->apiRegion() . "/villages/$request->district_id.json");
        return $this->successMessage("data berhasil diambil", $villages->json());
    }

    public function show($id)
    {
        $village = Http::get($this->apiRegion() . "/village/$id.json");
        return $this->successMessage("data berhasil diambil", $village->json());
    }
}
