<?php

namespace App\Http\Requests\Web\Text;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTextRequest extends FormRequest
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
            'text_topic_id' => ['required', 'integer', 'exists:text_topics,id'],
            'topic' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'difficulty' => ['required', 'string', Rule::in(['easy', 'medium', 'hard'])],
            'read_link' => ['nullable', 'url', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'text_topic_id.required' => 'Loại văn bản là bắt buộc.',
            'text_topic_id.exists' => 'Loại văn bản không hợp lệ.',
            'topic.max' => 'Chủ đề không được vượt quá 255 ký tự.',
            'name.required' => 'Tên văn bản là bắt buộc.',
            'author.required' => 'Tác giả là bắt buộc.',
            'difficulty.required' => 'Mức độ là bắt buộc.',
            'difficulty.in' => 'Mức độ không hợp lệ.',
            'read_link.url' => 'Link đọc không đúng định dạng.',
        ];
    }
}
