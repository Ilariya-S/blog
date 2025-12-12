<?php

namespace App\Services\Users\Controllers;

use App\Http\Controllers\Controller;

use App\Services\Users\Managers\RegistrationManager;
use App\Services\Users\Managers\SendLetterManager;
use App\Services\Users\Managers\SendNewVerifyLinkManager;
use App\Services\Users\Models\User;
use App\Services\Users\Managers\VerifyManager;
use App\Services\Users\Requests\NewLinkSendToUserRequest;
use App\Services\Users\Requests\RegistrationUserRequest;
use App\Services\Users\Requests\AutorizationUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct(
        private RegistrationManager $registrtioManager,
        private SendLetterManager $sendLetterManager,
        private SendNewVerifyLinkManager $sendNewVerufyLinkManager,
        private VerifyManager $verifyManager
    ) {
    }

    // реєстрація користувача
    public function registration(RegistrationUserRequest $request)
    {
        $payload = $request->validated();
        $user = $this->registrtioManager->store($payload);
        $this->sendLetterManager->send($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful. Please check your email for the verification link.',
        ]);
    }

    // верифікація пошти користувача
    public function verify($id, $hash)
    {
        $user = User::find($id);
        if ($user) {
            return $this->verifyManager->verify($user, $hash);
        } else {
            return response()->json(['message' => 'User not found.'], 404);
        }
    }

    // надсиланя новго посилання на верифікацію пошти
    public function newlink(NewLinkSendToUserRequest $request)
    {
        $payload = $request->validated();
        $user = User::where('email', $payload->email)->first();
        if ($user) {
            return $this->sendNewVerufyLinkManager->send($user);
        }
    }

    // авторизація користувача
    public function login(AutorizationUserRequest $request)
    {
        $payload = $request->validated();

        // Спроба отримати токен
        if (!$token = auth()->attempt($payload)) {
            return response()->json(['message' => 'Invalid login credentials.'], 401);
        }

        $user = auth()->user();

        // Перевірка верифікації пошти
        if (!$user->hasVerifiedEmail()) {
            auth()->logout(); // Викидаємо, якщо не підтвердив

            return response()->json([
                'status' => 'error',
                'message' => 'Your email address is not verified. Please check your inbox.',
            ], 403);
        }

        // return $this->respondWithToken($token);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }

    // вихід користувача
    public function destroy()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    // виршити чи потрібні
    /*
    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
        return response()->json([
            'access_token' => (auth()->refresh()),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
    */
}
