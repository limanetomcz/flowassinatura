<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyFormRequest extends FormRequest
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
        $companyId = $this->route('company') ?? $this->route('id');
        
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies')->ignore($companyId),
            ],
            'document' => [
                'required',
                'string',
                'max:255',
                Rule::unique('companies')->ignore($companyId),
            ],
            'contact_email' => 'required|email|max:255',
            'contact_number' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O campo nome é obrigatório.',
            'name.string' => 'O campo nome deve ser um texto.',
            'name.max' => 'O campo nome não pode ter mais que 255 caracteres.',
            'name.unique' => 'Este nome de empresa já está em uso.',
            'document.required' => 'O campo documento é obrigatório.',
            'document.string' => 'O campo documento deve ser um texto.',
            'document.max' => 'O campo documento não pode ter mais que 255 caracteres.',
            'document.unique' => 'Este documento já está em uso.',
            'contact_email.required' => 'O campo e-mail de contato é obrigatório.',
            'contact_email.email' => 'Digite um e-mail válido.',
            'contact_email.max' => 'O campo e-mail não pode ter mais que 255 caracteres.',
            'contact_number.required' => 'O campo número de contato é obrigatório.',
            'contact_number.string' => 'O campo número de contato deve ser um texto.',
            'contact_number.max' => 'O campo número de contato não pode ter mais que 255 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'document' => 'documento',
            'contact_email' => 'e-mail de contato',
            'contact_number' => 'número de contato',
        ];
    }
}
