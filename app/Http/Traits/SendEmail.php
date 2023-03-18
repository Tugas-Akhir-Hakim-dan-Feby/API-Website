<?php

namespace App\Http\Traits;

use App\Models\User;
use App\Notifications\SendEmailActivation;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

trait SendEmail
{
    public static function sendEmailActivation($email)
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            $token = Password::createToken(
                $user
            );

            $url = url("/auth/activation-account?token=$token&email=$user->email");
            return Notification::route('mail', $user->email)->notify(new SendEmailActivation($url));
        }
    }

    public static function sendEmailResetPassword($email)
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            $token = Password::createToken(
                $user
            );

            $url = url("/auth/activation-account?token=$token&email=$user->email");
            return Notification::route('mail', $user->email)->notify(new SendResetPassword($url));
        }
    }
}
