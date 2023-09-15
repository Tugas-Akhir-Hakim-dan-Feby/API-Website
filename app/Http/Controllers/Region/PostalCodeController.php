<?php

namespace App\Http\Controllers\Region;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class PostalCodeController extends Controller
{
    public function index(Request $request)
    {
        $postalCodes = File::get(public_path('assets/dataset/kodepos.json'));
        $postalCodes = json_decode($postalCodes, true);
        $postalCodes = array_filter($postalCodes);
        $postalCodes = collect($postalCodes);

        if ($request->has('village')) {
            // $postalCodes = $postalCodes->where('subdistrict', $request->village);
            $postalCodes = $postalCodes->where('subdistrict', 'Puntang');
        }

        $postalCodes = $postalCodes->all();

        return response()->json($postalCodes);
    }
}
