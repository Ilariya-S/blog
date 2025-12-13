<?php

namespace App\Services\Users\Controllers;
//від ларавел
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
//менеджери
use App\Services\Users\Managers\RegistrationManager;
use App\Services\Users\Managers\AutorizationManager;
//валідація даних + модель
use App\Services\Users\Models\User;
use App\Services\Users\Requests\NewLinkSendToUserRequest;
use App\Services\Users\Requests\RegistrationUserRequest;
use App\Services\Users\Requests\AutorizationUserRequest;
use App\Services\Users\Requests\RecoveryPaswordRequest;
use App\Services\Users\Requests\ResetPasswordRequest;
//помилки
use App\Services\Users\Exceptions\AuthorizationException;
use App\Services\Users\Exceptions\VerificationException;



class AuthController extends Controller
{
    public function __construct(
        private RegistrationManager $registrtioManager,
        private AutorizationManager $authManager
    ) {
    }

    // реєстрація користувача
    public function registration(RegistrationUserRequest $request)
    {
        $payload = $request->validated();
        $user = $this->registrtioManager->register($payload);
        $this->registrtioManager->sendLink($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful. Please check your email for the verification link.',
        ]);
    }

    // верифікація пошти користувача
    public function verify($id, $hash)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
        try {
            $status = $this->registrtioManager->verify($user, $hash);

            if ($status === false) {
                return response()->json(['message' => 'Email already verified.'], 200);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Email successfully verified. You can now login.',
            ]);

        } catch (VerificationException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

    // надсиланя новго посилання на верифікацію пошти
    public function newlink(NewLinkSendToUserRequest $request)
    {
        $payload = $request->validated();
        $user = User::where('email', $payload['email'])->first();

        if (!$user) {
            return response()->json([
                'status' => 'info',
                'message' => 'If the user exists and is not verified, a new verification link has been sent.',
            ], 200);
        }

        $linkSent = $this->registrtioManager->sendNewLink($user);
        if ($linkSent) {
            return response()->json([
                'status' => 'success',
                'message' => 'Verification link sent!',
            ]);
        }
        return response()->json([
            'status' => 'info',
            'message' => 'Email is already verified.',
        ], );
    }

    // авторизація користувача
    public function login(AutorizationUserRequest $request)
    {
        $payload = $request->validated();
        try {
            $token = $this->authManager->login($payload);

            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
                'user' => auth()->user(),
            ]);

        } catch (AuthorizationException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        } catch (VerificationException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], $e->getCode());
        }
    }

    // вихід користувача
    public function destroy()
    {
        Auth::logout();
        return response()->json(['message' => 'Successfully logged out']);
    }
    //відображати профіль користувача, можливо не потрбіний
    public function me(Request $request)
    {
        return response()->json($request->user());
    }
    //оновлення токена, коли час дії минув
    public function refresh()
    {
        return response()->json([
            'access_token' => Auth::refresh(),
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }
    //надсилання листа: оновлення пароля
    public function recoveryPassword(RecoveryPaswordRequest $request)
    {
        $payload = $request->validated();
        $status = Password::sendResetLink($payload);
        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => 'success',
                'message' => __($status),
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => __($status),
        ], 400);
    }
    //новий пароль
    public function resetPassword(ResetPasswordRequest $request)
    {
        $payload = $request->validated();
        $status = $this->authManager->newPassword($payload);
        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => 'success',
                'message' => __($status),
            ]);
        }
        return response()->json([
            'status' => 'error',
            'message' => __($status),
        ], 400);

    }
}