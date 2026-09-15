<?php

namespace App\Http\Requests\Admin\Legal;

use Illuminate\Foundation\Http\FormRequest;

class LegalPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'last_updated' => ['required', 'string', 'max:100'],
            'published' => ['nullable', 'boolean'],
            'version' => ['nullable', 'integer'],
        ];
    }
}
