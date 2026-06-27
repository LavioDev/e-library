<?php

namespace App\Http\Requests\Web\TextComment;

use Illuminate\Foundation\Http\FormRequest;

class StoreTextCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'content' => ['required', 'string', 'max:2000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'content.required' => 'Nội dung bình luận là bắt buộc.',
            'content.max' => 'Bình luận không được vượt quá 2000 ký tự.',
        ];
    }
}

