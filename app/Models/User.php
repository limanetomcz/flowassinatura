<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Classe User - Representa um usuário autenticável da aplicação.
 *
 * Gerencia os dados básicos do usuário, autenticação e atributos relacionados.
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Atributos que podem ser preenchidos em massa.
     *
     * Inclui campos essenciais para criação e atualização de usuário.
     * Adicionamos 'is_admin' para controle de privilégios administrativos.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',        // Nome completo do usuário
        'email',       // Email único para login/autenticação
        'password',    // Senha criptografada
        'is_admin',    // Flag booleana para controle de acesso administrativo
        'company_id',  // ID da empresa à qual o usuário pertence
    ];

    /**
     * Atributos que devem ser ocultados durante a serialização para arrays ou JSON.
     *
     * Evita exposição de dados sensíveis em respostas API ou views.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',        // Senha nunca exposta
        'remember_token',  // Token de "lembrar-me" para sessões
    ];

    /**
     * Casting automático de atributos para tipos nativos do PHP.
     *
     * Facilita o uso dos atributos com seus tipos corretos sem necessidade de conversão manual.
     *
     * @var array<string, string>
     */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected $casts = [
        'email_verified_at' => 'datetime', // Data/hora da verificação do email
        'password' => 'hashed',            // Senha é automaticamente criptografada
        'is_admin' => 'boolean',           // Converte para bool para facilitar verificações lógicas
    ];

}
