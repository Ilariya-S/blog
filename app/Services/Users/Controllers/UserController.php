<?php

namespace App\Services\Users\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Users\Requests\RegistrationUserRequest;
use App\Services\Users\Managers\RegistrationManager;
use App\Services\Users\Managers\SendLetterManager;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

use App\Services\Users\Models\User;

class UserController extends Controller
{
    public function __construct(private RegistrationManager $registrtiomanager, private SendLetterManager $sendlettermanager)
    {
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

    public function verifyemail()
    {
        $resendLink = route('verification.send');
        return response()->json([
            'status' => 'success',
            'message' => 'Thank you for registering. Please check your email.',
            'info' => 'Didn`t receive the link?',
            'resend_link' => $resendLink,
            'resend_text' => 'Send verification link again',
        ]);
    }
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return response()->json([
            'massage' => 'Registration was successful, email was verified'
        ]);
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
    public function autorisation($request)
    {
        $payload = $request->validated();
        // $student = $this->studentService->store($payload);
        //return response()->json($student);
    }
}