<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

/**
 * Middleware responsável por proteger a aplicação contra ataques CSRF (Cross-Site Request Forgery).
 *
 * Garante que todas as requisições POST, PUT, PATCH e DELETE contenham um token válido,
 * evitando que requisições maliciosas sejam aceitas.
 */
class VerifyCsrfToken extends Middleware
{
    /**
     * Lista das URIs (rotas) que devem ser excluídas da verificação CSRF.
     *
     * Adicione aqui as rotas que precisam ser acessadas sem verificação de token,
     * como webhooks externos ou APIs públicas, se necessário.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Exemplo:
        // 'webhook/*',
    ];
}
