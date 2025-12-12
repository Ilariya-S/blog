<?php
namespace App\Services\Users\Managers;

use Illuminate\Support\Facades\Auth;
use App\Services\Users\Exceptions\AuthorizationException;
use App\Services\Users\Exceptions\VerificationException;

class AutorizationManager
{
    public function login(array $data): string
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
}
