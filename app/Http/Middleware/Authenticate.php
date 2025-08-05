<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Mensagem padrão para usuário não autenticado.
     */
    private const UNAUTHORIZED_MESSAGE = 'Usuário não autenticado.';

    /**
     * Intercepta a requisição para garantir que o usuário esteja autenticado.
     *
     * Caso contrário, retorna resposta adequada (401) ou redireciona para login.
     *
     * @param  Request  $request  A requisição HTTP recebida.
     * @param  Closure  $next     Próximo middleware na pipeline.
     * @param  string|null ...$guards  Possíveis guardas (não utilizados aqui, mas padrão Laravel).
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if (!$this->isAuthenticated()) {
            return $this->unauthorizedResponse($request);
        }

        return $next($request);
    }

    /**
     * Verifica se o usuário está autenticado.
     *
     * @return bool
     */
    private function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * Retorna resposta apropriada para requisição não autenticada.
     * Retorna JSON para API e redireciona para login para web.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    private function unauthorizedResponse(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => self::UNAUTHORIZED_MESSAGE], 401);
        }

        return redirect()->route('login');
    }
}
