<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->json([
            'user' => Auth::user(),
        ]);
    }
}
