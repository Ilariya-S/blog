<?php

namespace App\Services\Users\Managers;

use App\Services\Users\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Services\Users\Exceptions\VerificationException;

class VerifyManager
{
    public function verify(User $user, $hash): bool
    {
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            throw new VerificationException('Invalid verification link.', 403);
        }

        if ($user->hasVerifiedEmail()) {
            return false;
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return true;
    }
}
