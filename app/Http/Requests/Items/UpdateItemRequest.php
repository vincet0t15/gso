<?php

namespace App\Http\Requests\Items;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends StoreItemRequest
{
    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'item_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('items', 'item_code')->ignore($this->route('item')),
            ],
        ];
    }
}
