<?php

namespace App\Services\Comments\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'post_id' => 'required|integer|exists:posts,id',
            'body' => 'required|string|min:1|max:2000',
            'parent_id' => 'sometimes|integer|exists:comments,id',
        ];
    }
}
