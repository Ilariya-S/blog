<?php
namespace App\Services\Users\Managers;

use App\Services\Users\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use App\Services\Users\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Services\Users\Exceptions\VerificationException;
use Illuminate\Support\Facades\Hash;
class RegistrationManager
{
    public function __construct(private UserRepository $repository)
    {
    }

    public function register(array $data)
    {
        //Хешуємо пароль перед збереженням
        $data['password'] = Hash::make($data['password']);
        if (isset($data['password_confirmation'])) {
            unset($data['password_confirmation']);
        }
        return $this->repository->create($data);
    }

    public function sendLink(User $newUser)
    {
        event(new Registered($newUser));
    }

    public function sendNewLink(string $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                'status' => 'info',
                'message' => 'If the user exists and is not verified, a new verification link has been sent.',
            ], 200);
        }

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
