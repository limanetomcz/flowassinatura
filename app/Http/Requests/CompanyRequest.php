<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:companies,name',
            'document' => [
                'required',
                'regex:/^\d{11}$|^\d{14}$/',
            ],
            'contact_number' => 'required',
            'contact_email' => 'nullable|email',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name is required.',
            'name.unique' => 'A company with this name already exists.',
            'document.required' => 'The document is required.',
            'document.regex' => 'The document must be a valid CPF (11 digits) or CNPJ (14 digits).',
            'contact_number.required' => 'The phone number is required.',
            'contact_email.email' => 'The contact email must be valid.',
        ];
    }
}
