<?php

namespace App\Services\Users\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Users\Requests\RegistrationUserRequest;
use App\Services\Users\Managers\RegistrationManager;
use App\Services\Users\Managers\SendLetterManager;


class UserController extends Controller
{
    public function __construct(private RegistrationManager $registrtiomanager, private SendLetterManager $sendlettermanager)
    {
    }
    public function registration(RegistrationUserRequest $request)
    {
        $payload = $request->validated();
        $user = $this->registrtiomanager->store($payload);
        //$user = $this->sendlettermanager->store($payload);
        return response()->json($user);
    }
    public function autorisation($request)
    {
        $payload = $request->validated();
        // $student = $this->studentService->store($payload);
        //return response()->json($student);
    }
}