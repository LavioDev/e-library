<?php

namespace App\Http\Requests\Web\TextTopic;

use App\Models\TextTopic;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTextTopicRequest extends FormRequest
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
        /** @var TextTopic $textTopic */
        $textTopic = $this->route('textTopic');

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('text_topics', 'name')->ignore($textTopic->id)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên loại văn bản là bắt buộc.',
            'name.unique' => 'Tên loại văn bản đã tồn tại.',
        ];
    }
}
