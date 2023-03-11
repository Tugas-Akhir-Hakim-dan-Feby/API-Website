<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $user->load('roles');

        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();

        $role = $user->roles->pluck('name');
        $token = $user->createToken('api', $role->toArray())->plainTextToken;

        $user = User::where('id', $user->id)->first();

        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }
}
