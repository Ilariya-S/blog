<?php

namespace App\Services\Users\Managers;

use App\Services\Users\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Verified;

class VerifyManager
{

    public function verify(User $user, $hash)
    {
        // 2. Перевірка хешу (безпека, хоча signed middleware це теж робить)
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link.'], 403);
        }

        // 3. Якщо вже верифікований
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 200);
        }

        // 4. Позначаємо як верифікований і запускаємо подію
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Email successfully verified. You can now login.',
        ]);
    }
}
