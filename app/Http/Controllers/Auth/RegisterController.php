<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Traits\SendEmail;
use App\Models\Role as ModelsRole;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Contracts\Role;

class RegisterController extends Controller
{
    use SendEmail;

    protected $userRepository;
    protected $roleRepository;

    public function __construct(UserRepository $userRepository, Role $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(RegisterRequest $request)
    {
        $request->merge([
            'password' => Hash::make($request->input('password')),
        ]);

        $validated = $request->validated();

        if ($validated['password'] !== $validated['password_confirmation']) {
            return response()->json([
                'message' => 'Konfirmasi password tidak sesuai',
                'status_code' => 422
            ], 422);
        }

        $user = DB::transaction(function () use ($request) {
            $role = $this->roleRepository->findById(ModelsRole::GUEST, 'api');
            $user = $this->userRepository->create($request->validated());

            $user->assignRole($role);
            $this->sendEmail($user->email);

            return $user;
        });

        return response()->json([
            'message' => 'Registrasi berhasil',
            'data' => $user,
            'status_code' => 201
        ], 201);
    }
}
