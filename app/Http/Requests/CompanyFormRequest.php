<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:5',
            'document' => 'required|string',
            'contact_email' => 'required|string',
            'contact_number' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser um texto.',
            'name.max' => 'O campo nome não pode ter mais que 5 caracteres.',
            'document.required' => 'O campo documento é obrigatório.',
            'document.string' => 'O campo documento deve ser um texto.',
            'contact_email.required' => 'O campo e-mail de contato é obrigatório.',
            'contact_email.string' => 'O campo e-mail de contato deve ser um texto.',
            'contact_number.required' => 'O campo número de contato é obrigatório.',
            'contact_number.string' => 'O campo número de contato deve ser um texto.',
        ];
    }
}
