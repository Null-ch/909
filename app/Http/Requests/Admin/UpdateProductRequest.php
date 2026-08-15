<?php

namespace App\Http\Requests\Admin;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        /** @var Product $product */
        $product = $this->route('product');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('products', 'slug')->ignore($product->id),
            ],
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'old_price' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', Rule::exists('categories', 'id')],
            'attributes' => ['nullable', 'array'],
            'attributes.*.name' => ['nullable', 'string', 'max:255'],
            'attributes.*.value' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,jpg,png,webp,gif', 'max:5120'],
            'delete_image_ids' => ['nullable', 'array'],
            'delete_image_ids.*' => ['integer', Rule::exists('product_images', 'id')],
            'image_order' => ['nullable', 'array'],
            'image_order.*' => ['integer', Rule::exists('product_images', 'id')],
            'main_image_id' => ['nullable', 'integer', Rule::exists('product_images', 'id')],
            'main_new_image' => ['nullable', 'integer', 'min:0'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Укажите название товара.',
            'sku.required' => 'Укажите артикул.',
            'sku.unique' => 'Такой артикул уже используется.',
            'slug.unique' => 'Такой URL (slug) уже используется.',
            'price.required' => 'Укажите цену.',
            'quantity.required' => 'Укажите остаток на складе.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
            'category_ids' => array_values(array_filter($this->input('category_ids', []))),
            'delete_image_ids' => array_values(array_filter($this->input('delete_image_ids', []))),
        ]);
    }
}
