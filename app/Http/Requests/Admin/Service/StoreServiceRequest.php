<?php

namespace App\Http\Requests\Admin\Service;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'price_amd' => ['required', 'string', 'max:255'],
            'price_currency' => ['nullable', 'string', 'max:10'],
            'show_price' => ['nullable', 'boolean'],
            'price_label' => ['nullable', 'string', 'max:255'],
            'popular' => ['nullable', 'boolean'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'icon' => ['nullable', 'string', 'max:255'],
            'cta_text' => ['nullable', 'string', 'max:255'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'published' => ['nullable', 'boolean'],
            'features' => ['nullable', 'array'],
            'features.*.text' => ['required_with:features', 'string', 'max:255'],
            'features.*.sort_order' => ['nullable', 'integer'],
        ];
    }
}
