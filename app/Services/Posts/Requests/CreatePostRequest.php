<?php
namespace App\Services\Posts\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:25|max:255,',
            'body' => 'required|string|min:50',
            'category' => 'required|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255'
        ];
    }

}
