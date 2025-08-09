<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
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
            'nome' => 'required|unique:empresas,nome',
            'documento' => [
                'required',
                'regex:/^\d{11}$|^\d{14}$/',
            ],
            'telefone' => 'required',
            'email_contato' => 'nullable|email',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome é obrigatório.',
            'nome.unique' => 'Já existe uma empresa com este nome.',
            'documento.required' => 'O documento é obrigatório.',
            'documento.regex' => 'O documento deve ser um CPF (11 dígitos) ou CNPJ (14 dígitos) válido.',
            'telefone.required' => 'O telefone é obrigatório.',
            'email_contato.email' => 'O e-mail de contato deve ser válido.',
        ];
    }
}
