<?php

namespace App\Services\Users\Controllers;
//від ларавел
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, Password};
//менеджери
use App\Services\Users\Managers\{RegistrationManager, AuthorizationManager};
//валідація даних + модель
use App\Services\Users\Models\User;
use App\Services\Users\Requests\{
    NewLinkSendToUserRequest,
    RegistrationUserRequest,
    AuthorizationUserRequest,
    RecoveryPasswordRequest,
    ResetPasswordRequest,
};
//помилки
use App\Services\Users\Exceptions\{AuthorizationException, VerificationException};

class AuthController extends Controller
{
    public function __construct(
        private RegistrationManager $registrationManager,
        private AuthorizationManager $authManager
    ) {
    }

    // реєстрація користувача
    public function registration(RegistrationUserRequest $request)
    {
        $payload = $request->validated();
        $user = $this->registrationManager->register($payload);
        $this->registrationManager->sendLink($user);
        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful. Please check your email for the verification link.',
        ]);
    }

    // верифікація пошти користувача
    public function verify($id, $hash)
    {
        $user = User::findOrFail($id);
        try {
            $status = $this->registrationManager->verify($user, $hash);

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
    public function newLink(NewLinkSendToUserRequest $request)
    {
        $payload = $request->validated();

        $linkSent = $this->registrationManager->sendNewLink($payload);
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
    public function login(AuthorizationUserRequest $request)
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
    /*public function me(Request $request)
    {
        return response()->json($request->user());
    }*/

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
    public function recoveryPassword(RecoveryPasswordRequest $request)
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