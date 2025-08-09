<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

/**
 * Middleware responsável por encriptar os cookies da aplicação.
 *
 * Garante que os cookies armazenados no navegador do cliente estejam protegidos,
 * evitando que sejam lidos ou modificados indevidamente.
 */
class EncryptCookies extends Middleware
{
    /**
     * Lista dos cookies que NÃO devem ser encriptados.
     *
     * Você pode adicionar nomes de cookies aqui para que eles sejam enviados
     * em texto claro, por exemplo, cookies que precisam ser acessados via JavaScript.
     *
     * @var array<int, string>
     */
    protected $except = [
        // 'cookie_name_exemplo',
    ];
}
