<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user') ?? $this->route('id');
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'is_admin' => 'boolean',
            'company_id' => 'nullable|exists:companies,id',
        ];

        // Regras de senha
        if ($isUpdate) {
            // Na atualização, senha é opcional
            $rules['password'] = 'nullable|string|min:8|confirmed';
        } else {
            // Na criação, senha é obrigatória
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser um texto.',
            'name.max' => 'O nome não pode ter mais de 255 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Digite um email válido.',
            'email.max' => 'O email não pode ter mais de 255 caracteres.',
            'email.unique' => 'Este email já está em uso.',
            'password.required' => 'A senha é obrigatória.',
            'password.string' => 'A senha deve ser um texto.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação de senha não confere.',
            'is_admin.boolean' => 'O campo administrador deve ser verdadeiro ou falso.',
            'company_id.nullable' => 'O campo empresa é opcional.',
            'company_id.exists' => 'A empresa selecionada não existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nome',
            'email' => 'email',
            'password' => 'senha',
            'password_confirmation' => 'confirmação de senha',
            'is_admin' => 'administrador',
            'company_id' => 'empresa',
        ];
    }
}
