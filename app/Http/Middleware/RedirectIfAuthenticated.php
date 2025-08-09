<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Middleware responsável por redirecionar usuários já autenticados.
 *
 * Geralmente utilizado para impedir que usuários logados acessem páginas de login ou registro,
 * redirecionando-os para uma rota padrão (ex: dashboard ou home).
 */
class RedirectIfAuthenticated
{
    /**
     * Intercepta a requisição e verifica se o usuário está autenticado.
     *
     * Caso esteja, redireciona para a rota principal da aplicação.
     * Caso contrário, permite que a requisição prossiga normalmente.
     *
     * @param  Request  $request  A requisição HTTP recebida.
     * @param  Closure  $next     Próximo middleware na pipeline.
     * @param  string|null ...$guards  Guardas (não utilizados aqui, mas padrão Laravel).
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (Auth::check()) {
            // Usuário já autenticado: redireciona para página inicial (exemplo: /home)
            return redirect('/home');
        }

        // Usuário não autenticado: continua com o fluxo normal da requisição
        return $next($request);
    }
}
