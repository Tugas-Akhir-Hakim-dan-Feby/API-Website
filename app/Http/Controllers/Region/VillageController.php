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
        try {
            $villages = Http::get($this->apiRegion() . "/villages/$request->district_id.json");

            if (!$villages->successful() || $villages->status() != 200) {
                return null;
            }

            return $this->successMessage("data berhasil diambil", $villages->json());
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function show($id)
    {
        try {
            $village = Http::get($this->apiRegion() . "/village/$id.json");

            if (!$village->successful() || $village->status() != 200) {
                return null;
            }

            return $this->successMessage("data berhasil diambil", $village->json());
        } catch (\Throwable $th) {
            return null;
        }
    }
}
