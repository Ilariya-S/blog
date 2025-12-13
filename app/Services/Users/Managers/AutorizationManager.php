<?php
namespace App\Services\Users\Managers;

use Illuminate\Support\Facades\Auth;
use App\Services\Users\Exceptions\AuthorizationException;
use App\Services\Users\Exceptions\VerificationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AutorizationManager
{
    public function login(array $data)
    {
        if (!$token = Auth::attempt($data)) {
            throw new AuthorizationException('Invalid login credentials.', 401);
        }

        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            throw new VerificationException('Your email address is not verified. Please check your inbox.', 403);
        }

        return $token;
    }
    public function newPassword(array $data)
    {
        return Password::reset($data, function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        });
    }
}
