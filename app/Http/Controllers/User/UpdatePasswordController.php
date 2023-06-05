<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Traits\MessageFixer;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    use MessageFixer;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(UpdatePasswordRequest $request)
    {
        DB::beginTransaction();

        $user = Auth::user();
        $user = $this->userRepository->findOrFail($user->uuid);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'messages' => 'password yang digunakan salah',
                'status_code' => 400
            ], 400);
        }

        try {
            $this->userRepository->update($user->id, [
                'password' => Hash::make($request->new_password)
            ]);

            DB::commit();
            return $this->successMessage('password berhasil diperbaharui', []);
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorMessage($th->getMessage());
        }
    }
}
