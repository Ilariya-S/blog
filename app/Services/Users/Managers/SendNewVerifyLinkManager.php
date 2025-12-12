<?php

namespace App\Services\Users\Managers;

use App\Services\Users\Models\User;

class SendNewVerifyLinkManager
{
    public function send(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }
        $user->sendEmailVerificationNotification();
        return true;
    }
}
