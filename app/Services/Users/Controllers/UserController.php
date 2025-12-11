<?php

namespace App\Services\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Users\Requests\RegistrationUserRequest;
use App\Services\Users\Managers\RegistrationManager;
use App\Services\Users\Managers\SendLetterManager;



use Illuminate\Http\Request;
use App\Services\Users\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function __construct(
        private RegistrationManager $registrtiomanager,
        private SendLetterManager $sendlettermanager
    ) {
    }
    public function registration(RegistrationUserRequest $request)
    {
        $payload = $request->validated();
        $user = $this->registrtiomanager->store($payload);
        $this->sendlettermanager->send($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful. Please check your email for the verification link.',
        ]);
    }

    /*public function verifyemail()
    {
        $resendLink = route('verification.send');
        return response()->json([
            'status' => 'success',
            'message' => 'Thank you for registering. Please check your email.',
            'info' => 'Didn`t receive the link?',
            'resend_link' => $resendLink,
            'resend_text' => 'Send verification link again',
        ]);
    }*/
    public function verify($id, $hash, Request $request)
    {// 1. Знаходимо користувача за ID з URL
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

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
            'message' => 'Email successfully verified. You can now login.'
        ]);
        /*
        $request->fulfill();
        return response()->json([
            'massage' => 'Registration was successful, email was verified'
        ]);*/
    }
    public function newlink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        // 2. Знаходимо користувача за email
        $user = User::where('email', $request->email)->first();

        // 3. Перевірка: пошта вже верифікована
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 'info',
                'message' => 'Email is already verified.',
            ], 200);
        }

        // 4. Надсилаємо лист
        $user->sendEmailVerificationNotification();

        return response()->json([
            'status' => 'success',
            'message' => 'Verification link sent!',
        ]);
    }


    ///дивна авторизація
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        // Спроба отримати токен
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['message' => 'Invalid login credentials.'], 401);
        }

        $user = auth()->user();

        // Перевірка верифікації пошти
        if (!$user->hasVerifiedEmail()) {
            auth()->logout(); // Викидаємо, якщо не підтвердив
            return response()->json([
                'status' => 'error',
                'message' => 'Your email address is not verified. Please check your inbox.'
            ], 403);
        }

        return $this->respondWithToken($token);
    }

    public function destroy()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}