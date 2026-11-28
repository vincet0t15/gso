<?php

namespace App\Http\Requests\Units;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends StoreUnitRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('units', 'code')->ignore($this->route('unit')),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('units', 'name')->ignore($this->route('unit')),
            ],
        ];
    }
}
