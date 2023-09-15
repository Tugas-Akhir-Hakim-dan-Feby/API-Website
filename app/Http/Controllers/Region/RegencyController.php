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
        try {
            $regencies = Http::get($this->apiRegion() . "/regencies/$request->province_id.json");

            if (!$regencies->successful() || $regencies->status() != 200) {
                return null;
            }

            return $this->successMessage("data berhasil diambil", $regencies->json());
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function show($id)
    {
        try {
            $regency = Http::get($this->apiRegion() . "/regency/$id.json");

            if (!$regency->successful() || $regency->status() != 200) {
                return null;
            }

            return $this->successMessage("data berhasil diambil", $regency->json());
        } catch (\Throwable $th) {
            return null;
        }
    }
}
