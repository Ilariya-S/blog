<?php

namespace app\Services\Posts\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostListRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('tags') && is_string($this->tags)) {
            $this->merge([
                'tags' => explode(',', $this->tags),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'type' => 'sometimes|string|in:all,my,popular,unanswered',
            'tag' => 'sometimes|array',
            'tags.*' => 'string|exists:tags,title',
        ];
    }
}
