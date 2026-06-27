<?php

namespace App\Http\Requests\Web\Text;

use Illuminate\Foundation\Http\FormRequest;

class DestroyTextRequest extends FormRequest
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
