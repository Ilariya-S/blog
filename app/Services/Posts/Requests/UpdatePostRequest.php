<?php
namespace App\Services\Posts\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|min:25|max:255,',
            'body' => 'sometimes|string|min:50',
            'category' => 'sometimes|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255'
        ];
    }

}
