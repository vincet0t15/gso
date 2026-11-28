<?php

namespace App\Http\Requests\Offices;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateOfficeRequest extends StoreOfficeRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'office_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('offices', 'office_name')->ignore($this->route('office')),
            ],
        ];
    }
}
