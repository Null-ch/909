<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDeliveryMethodRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->sharedRules();
    }

    /**
     * @return array<string, mixed>
     */
    protected function sharedRules(?int $ignoreId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('delivery_methods', 'slug')->ignore($ignoreId),
            ],
            'description' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'rates' => ['nullable', 'array'],
            'rates.*.name' => ['nullable', 'string', 'max:255'],
            'rates.*.min_weight' => ['nullable', 'numeric', 'min:0'],
            'rates.*.max_weight' => ['nullable', 'numeric', 'min:0'],
            'rates.*.min_volume' => ['nullable', 'numeric', 'min:0'],
            'rates.*.max_volume' => ['nullable', 'numeric', 'min:0'],
            'rates.*.max_length' => ['nullable', 'numeric', 'min:0'],
            'rates.*.max_width' => ['nullable', 'numeric', 'min:0'],
            'rates.*.max_height' => ['nullable', 'numeric', 'min:0'],
            'rates.*.price' => ['nullable', 'numeric', 'min:0'],
            'rates.*.is_active' => ['sometimes', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
