<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $user->load('roles');

        return response()->json([
            'user' => $user,
        ]);
    }
}
