<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(LoginRequest $request)
    {
        $user = $this->userRepository->findByEmail($request->email);
        if (!$user) {
            return response()->json([
                'message' => 'email atau password salah',
                'status_code' => 400
            ], 400);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'email atau password salah',
                'status_code' => 400
            ], 400);
        }

        if (!$user->remember_token) {
            return response()->json([
                'message' => 'harap verifikasi email terlebih dahulu',
                'status_code' => 400
            ], 400);
        }

        $role = $user->roles->pluck('name');
        $token = $user->createToken('api', $role->toArray())->plainTextToken;

        return response()->json([
            'message' => 'berhasil login',
            'status_code' => 200,
            'token' => $token,
            'user' => $user
        ], 200);
    }
}
