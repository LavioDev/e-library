<?php

namespace App\Http\Requests\Web\TextTopic;

use Illuminate\Foundation\Http\FormRequest;

class DestroyTextTopicRequest extends FormRequest
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
        return [];
    }
}
