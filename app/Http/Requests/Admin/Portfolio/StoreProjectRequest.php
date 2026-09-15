<?php

namespace App\Http\Requests\Admin\Portfolio;

use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'client' => ['required', 'string', 'max:255'],
            'summary' => ['required', 'string'],
            'overview' => ['nullable', 'string'],
            'challenge' => ['required', 'string'],
            'solution' => ['required', 'string'],
            'services' => ['nullable', 'array'],
            'results' => ['nullable', 'array'],
            'year' => ['required', 'string', 'max:50'],
            'live_url' => ['nullable', 'string', 'max:500'],
            'hero_image' => ['nullable', 'string', 'max:500'],
            'seo_title' => ['nullable', 'string', 'max:255'],
            'seo_description' => ['nullable', 'string'],
            'featured' => ['nullable', 'boolean'],
            'published' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'categories' => ['nullable', 'array'],
            'categories.*' => ['string', 'exists:portfolio_categories,id'],
            'technologies' => ['nullable', 'array'],
            'technologies.*' => ['string', 'exists:portfolio_technologies,id'],
            'images' => ['nullable', 'array'],
            'images.*.url' => ['required_with:images', 'string', 'max:500'],
            'images.*.alt' => ['nullable', 'string', 'max:255'],
            'images.*.caption' => ['nullable', 'string', 'max:255'],
            'images.*.sort_order' => ['nullable', 'integer'],
        ];
    }
}
