<?php

namespace App\Services\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthorizationUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
        ];
    }
}
