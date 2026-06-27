<?php

namespace App\Http\Requests\Web\ReadingClass;

use Illuminate\Foundation\Http\FormRequest;

class DestroyReadingClassRequest extends FormRequest
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

