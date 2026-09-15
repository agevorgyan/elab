<?php

namespace App\Http\Requests\Public;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:255',
            'phone' => 'required|string|min:5|max:50',
            'email' => 'nullable|email|max:255',
            'company' => 'nullable|string|max:255',
            'project_type' => 'nullable|string|max:100',
            'service' => 'nullable|string|max:100',
            'budget' => 'nullable|string|max:100',
            'message' => 'nullable|string|max:5000',
            'source' => 'nullable|string|max:100',
            'honeypot' => 'nullable|string|max:255',
            'website_url' => 'nullable|string|max:255',
            'hp_field' => 'nullable|string|max:255',
        ];
    }
}
