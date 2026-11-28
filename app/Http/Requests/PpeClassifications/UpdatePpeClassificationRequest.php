<?php

namespace App\Http\Requests\PpeClassifications;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdatePpeClassificationRequest extends StorePpeClassificationRequest
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
                'max:50',
                Rule::unique('ppe_classifications', 'code')->ignore($this->route('ppe_classification')),
            ],
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('ppe_classifications', 'name')->ignore($this->route('ppe_classification')),
            ],
        ];
    }
}
