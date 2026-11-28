<?php

namespace App\Http\Requests\Types;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateTypeRequest extends StoreTypeRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('types', 'name')->ignore($this->route('type')),
            ],
        ];
    }
}
