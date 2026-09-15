<?php

namespace App\Http\Requests\Admin\Cookie;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCookieSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'version' => ['nullable', 'integer'],
            'banner_enabled' => ['required', 'boolean'],
            'analytics_enabled' => ['required', 'boolean'],
            'marketing_enabled' => ['required', 'boolean'],
            'ga_measurement_id' => ['nullable', 'string', 'max:255'],
            'meta_pixel_id' => ['nullable', 'string', 'max:255'],
        ];
    }
}
