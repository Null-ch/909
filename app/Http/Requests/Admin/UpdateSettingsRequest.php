<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $nullableUrls = ['social_vk', 'social_telegram', 'social_whatsapp'];

        foreach ($nullableUrls as $field) {
            if ($this->input($field) === '') {
                $this->merge([$field => null]);
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'shop_name' => ['required', 'string', 'max:255'],
            'shop_description' => ['nullable', 'string', 'max:5000'],
            'about_text' => ['nullable', 'string', 'max:65000'],
            'footer_text' => ['nullable', 'string', 'max:5000'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif,svg', 'max:5120'],
            'favicon' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif,ico', 'max:1024'],
            'social_vk' => ['nullable', 'string', 'max:255', 'url'],
            'social_telegram' => ['nullable', 'string', 'max:255', 'url'],
            'social_whatsapp' => ['nullable', 'string', 'max:255', 'url'],
            'benefits' => ['nullable', 'string', 'max:65000', function (string $attribute, mixed $value, \Closure $fail): void {
                if ($value === null || $value === '') {
                    return;
                }

                json_decode($value);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    $fail('Поле «Карусель преимуществ» должно содержать корректный JSON.');
                }
            }],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'contact_address' => ['nullable', 'string', 'max:1000'],
            'contact_map_iframe' => ['nullable', 'string', 'max:65000'],
            'seo_meta_title' => ['nullable', 'string', 'max:255'],
            'seo_meta_keywords' => ['nullable', 'string', 'max:1000'],
            'seo_meta_description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'shop_name.required' => 'Укажите название магазина.',
            'contact_email.email' => 'Укажите корректный email.',
            'logo.image' => 'Логотип должен быть изображением.',
            'logo.max' => 'Размер логотипа не должен превышать 5 МБ.',
            'favicon.image' => 'Favicon должен быть изображением.',
            'favicon.max' => 'Размер favicon не должен превышать 1 МБ.',
            'social_vk.url' => 'Укажите корректную ссылку на ВКонтакте.',
            'social_telegram.url' => 'Укажите корректную ссылку на Telegram.',
            'social_whatsapp.url' => 'Укажите корректную ссылку на WhatsApp.',
        ];
    }
}
