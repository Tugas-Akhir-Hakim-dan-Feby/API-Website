<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\NewPasswordRequest;
use App\Http\Traits\MessageFixer;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class NewPasswordController extends Controller
{
    use MessageFixer;

    public function __invoke(NewPasswordRequest $request)
    {
        DB::beginTransaction();

        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => bcrypt($password)
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            DB::commit();

            if ($status == Password::PASSWORD_RESET) {
                return $this->successMessage('password berhasil diubah', []);
            } else {
                return $this->warningMessage(__($status));
            }
        } catch (\Throwable $th) {
            return $this->errorMessage($th->getMessage());
        }
    }
}
