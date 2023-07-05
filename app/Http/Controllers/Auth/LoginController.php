<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Traits\MessageFixer;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use MessageFixer;

    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(LoginRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = $this->userRepository->findByEmail($request->email);
            if (!$user) {
                return $this->warningMessage('email atau password salah');
            }

            if (!Hash::check($request->password, $user->password)) {
                return $this->warningMessage('email atau password salah');
            }

            if (!$user->remember_token) {
                return $this->warningMessage('harap verifikasi email terlebih dahulu');
            }

            $role = $user->roles->pluck('name');
            $token = $user->createToken('api', $role->toArray())->plainTextToken;

            DB::commit();

            return $this->successMessage('login sukses', $user, $token);
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->errorMessage($th->getMessage());
        }
    }

    public function checkRoleId($data, $id)
    {
        return in_array($id, $data->toArray());
    }
}
