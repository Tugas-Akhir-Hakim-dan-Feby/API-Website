<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(UpdatePasswordRequest $request)
    {
        $user = Auth::user();
        $user = $this->userRepository->findOrFail($user->id);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'messages' => 'password yang digunakan salah',
                'status_code' => 400
            ], 400);
        }

        return DB::transaction(function () use ($user, $request) {
            return $this->userRepository->update($user->id, [
                'password' => Hash::make($request->new_password)
            ]);
        });
    }
}
