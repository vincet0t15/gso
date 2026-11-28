<?php

namespace App\Http\Requests\PpeClassifications;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePpeClassificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', Rule::unique('ppe_classifications', 'code')],
            'name' => ['required', 'string', 'max:150', Rule::unique('ppe_classifications', 'name')],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'A PPE classification with this code already exists.',
            'name.unique' => 'A PPE classification with this name already exists.',
        ];
    }
}
