<?php
namespace App\Services\Users\Managers;

use App\Services\Users\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use App\Services\Users\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Services\Users\Exceptions\VerificationException;

class RegistrationManager
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function register(array $data)
    {
        return $this->repository->create($data);
    }

    public function sendLink(User $newuser)
    {
        event(new Registered($newuser));
    }

    public function sendNewLink(User $user)
    {
        if ($user->hasVerifiedEmail()) {
            return false;
        }
        $user->sendEmailVerificationNotification();
        return true;
    }

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
