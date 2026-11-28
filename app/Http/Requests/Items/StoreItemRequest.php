<?php

namespace App\Http\Requests\Items;

use App\Models\PpeClassification;
use App\Models\Type;
use App\Models\Unit;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreItemRequest extends FormRequest
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
            'item_code' => ['required', 'string', 'max:50', Rule::unique('items', 'item_code')],
            'item_name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif,webp', 'max:2048'],
            'type_id' => ['nullable', 'integer', Rule::exists(Type::class, 'id')],
            'ppe_classification_id' => ['nullable', 'integer', Rule::exists(PpeClassification::class, 'id')],
            'unit_id' => ['nullable', 'integer', Rule::exists(Unit::class, 'id')],
            'unit_cost' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'item_code.unique' => 'An item with this code already exists.',
        ];
    }
}
