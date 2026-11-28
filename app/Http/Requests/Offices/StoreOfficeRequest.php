<?php

namespace App\Http\Requests\Offices;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOfficeRequest extends FormRequest
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
            'office_name' => ['required', 'string', 'max:255', Rule::unique('offices', 'office_name')],
            'office_code' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'office_name.unique' => 'An office with this name already exists.',
        ];
    }
}
