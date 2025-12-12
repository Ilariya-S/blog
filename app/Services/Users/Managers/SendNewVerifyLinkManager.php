<?php

namespace App\Services\Users\Managers;

use App\Services\Users\Models\User;

class SendNewVerifyLinkManager
{
    public function send(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'info',
                'message' => 'Email is already verified.',
            ], 200);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'status' => 'success',
            'message' => 'Verification link sent!',
        ]);
    }
}
