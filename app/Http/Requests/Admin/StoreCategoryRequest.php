<?php

namespace App\Http\Requests\Admin;

use App\Services\CategoryService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreCategoryRequest extends FormRequest
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
        return [
            'parent_id' => ['nullable', 'integer', Rule::exists('categories', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', Rule::unique('categories', 'slug')],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $parentId = $this->input('parent_id');

            if ($parentId && app(CategoryService::class)->maxDepthForParent((int) $parentId) >= CategoryService::MAX_DEPTH) {
                $validator->errors()->add('parent_id', 'Максимальная вложенность категорий — '.CategoryService::MAX_DEPTH.' уровня.');
            }
        });
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Укажите название категории.',
            'slug.unique' => 'Такой URL (slug) уже используется.',
            'slug.regex' => 'Slug может содержать только латинские буквы, цифры и дефисы.',
            'image.image' => 'Загрузите корректный файл изображения.',
            'image.max' => 'Размер изображения не должен превышать 5 МБ.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'parent_id' => $this->input('parent_id') ?: null,
            'sort_order' => $this->input('sort_order', 0),
        ]);
    }
}
