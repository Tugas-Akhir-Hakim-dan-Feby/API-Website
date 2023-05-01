<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdateProfileController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(UpdateProfileRequest $request)
    {
        $user = Auth::user();
        $user = $this->userRepository->findOrFail($user->id);

            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'message' => 'Password yang digunakan salah',
                    'status_code' => 400
                ], 400);
            }

            $user->name = $request->name;
            $user->email = $request->email;
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            return response()->json([
                'message' => 'Profile berhasil diperbarui',
                'user' => $user
            ], 200);

        return DB::transaction(function () use ($user, $request) {
            return $this->userRepository->update($user->id, [
                'name' => Hash::make($request->new_name),
                'email' => Hash::make($request->new_email),
                'password' => Hash::make($request->new_password)

            ]);
        });
    }
}
