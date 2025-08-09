<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\EmpresaRequest;

class EmpresaRequestTest extends TestCase
{
    public function test_empresa_request_valida_campos_corretamente()
    {
        // Dados válidos para o teste
        $data = [
            'nome' => 'Minha Empresa',
            'documento' => 'dfghjkk',  // 11 dígitos - CPF válido no formato regex
            'telefone' => '123456789',
            'email_contato' => 'contato@empresa.com',
        ];

        // Instancia o FormRequest
        $request = new EmpresaRequest();

        // Recupera as regras de validação
        $rules = $request->rules();

        // Faz a validação dos dados com as regras
        $validator = Validator::make($data, $rules);

        // Verifica se a validação passa
        $this->assertTrue($validator->passes());

        // Testa dados inválidos - Exemplo nome vazio
        $dataInvalid = [
            'nome' => '',
            'documento' => '123',  // Inválido
            'telefone' => '',
            'email_contato' => 'email_invalido',
        ];

        $validatorInvalid = Validator::make($dataInvalid, $rules);

        // Verifica se a validação falha
        $this->assertFalse($validatorInvalid->passes());

        // Pode checar mensagens específicas, se quiser:
        $errors = $validatorInvalid->errors();

        $this->assertTrue($errors->has('nome'));
        $this->assertTrue($errors->has('documento'));
        $this->assertTrue($errors->has('telefone'));
        $this->assertTrue($errors->has('email_contato'));
    }
}
