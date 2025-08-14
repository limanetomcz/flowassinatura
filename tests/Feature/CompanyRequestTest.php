<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CompanyRequest;

class CompanyRequestTest extends TestCase
{
    public function testCompanyRequestValidation()
    {
        // Dados válidos para o teste
        $data = [
            'name' => 'My Company',
            'document' => '12345678912',  // 11 dígitos - CPF válido no formato regex
            'contact_number' => '123456789',
            'contact_email' => 'contact@empresa.com',
        ];

        // Instancia o FormRequest
        $request = new CompanyRequest();

        // Recupera as regras de validação
        $rules = $request->rules();

        // Faz a validação dos dados com as regras
        $validator = Validator::make($data, $rules);

        // Verifica se a validação passa
        $this->assertTrue($validator->passes());

        // Testa dados inválidos - Exemplo nome vazio
        $dataInvalid = [
            'name' => '',
            'document' => '123',  // Inválido
            'contact_number' => '',
            'contact_email' => 'invalid_email',
        ];

        $validatorInvalid = Validator::make($dataInvalid, $rules);

        // Verifica se a validação falha
        $this->assertFalse($validatorInvalid->passes());

        // Pode checar mensagens específicas, se quiser:
        $errors = $validatorInvalid->errors();

        $this->assertTrue($errors->has('name'));
        $this->assertTrue($errors->has('document'));
        $this->assertTrue($errors->has('contact_number'));
        $this->assertTrue($errors->has('contact_email'));
    }
}
