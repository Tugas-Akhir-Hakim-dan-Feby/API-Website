<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Traits\MessageFixer;
use App\Http\Traits\SendEmail;
use App\Models\Role as ModelsRole;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Contracts\Role;

class RegisterController extends Controller
{
    use SendEmail, MessageFixer;

    protected $userRepository;
    protected $roleRepository;

    public function __construct(UserRepository $userRepository, Role $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(RegisterRequest $request)
    {
        DB::beginTransaction();

        $request->merge([
            'password' => Hash::make($request->password),
            'uuid' => Str::uuid()
        ]);

        try {
            $role = $this->roleRepository->findById(User::MEMBER_APPLICATION, 'api');
            $user = $this->userRepository->create($request->all());

            $user->assignRole($role);
            $this->sendEmailActivation($user->email);

            DB::commit();
            return $this->createMessage('registrasi berhasil', $user);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorMessage($th->getMessage());
        }
    }
}
