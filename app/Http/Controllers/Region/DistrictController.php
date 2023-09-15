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
        try {
            $districts = Http::get($this->apiRegion() . "/districts/$request->regency_id.json");

            if (!$districts->successful() || $districts->status() != 200) {
                return null;
            }

            return $this->successMessage("data berhasil diambil", $districts->json());
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function show($id)
    {
        try {
            $district = Http::get($this->apiRegion() . "/district/$id.json");

            if (!$district->successful() || $district->status() != 200) {
                return null;
            }

            return $this->successMessage("data berhasil diambil", $district->json());
        } catch (\Throwable $th) {
            return null;
        }
    }
}
