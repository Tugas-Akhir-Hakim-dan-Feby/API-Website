<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class ActivationAccountController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Request $request)
    {
        $user = $this->userRepository->findByEmail($request->email);

        $request->merge([
            'password' => ''
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'email_verified_at' => Carbon::now()
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );


        if ($status == Password::PASSWORD_RESET) {
            $data = [
                'message' => 'akun berhasil diaktifkan',
                'status_code' => 200
            ];
        } else {
            $data = [
                'message' => 'token aktifasi akun kedaluwarsa',
                'status_code' => 400
            ];
        }

        return view('pages.email-activated', $data);
    }
}
