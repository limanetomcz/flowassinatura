<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Mensagem padrão para acesso negado.
     */
    private const ACCESS_DENIED_MESSAGE = 'Acesso negado. Você não tem permissão para acessar esta área.';

    /**
     * Intercepta a requisição e verifica se o usuário está autenticado e possui privilégios de administrador.
     *
     * Caso contrário, retorna resposta de acesso negado (HTTP 403).
     *
     * @param  Request  $request  A requisição HTTP recebida.
     * @param  Closure  $next     A próxima etapa/middleware da pipeline.
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->userIsAdmin()) {
            return $this->denyAccess($request);
        }

        return $next($request);
    }

    /**
     * Verifica se o usuário está autenticado e possui a flag 'is_admin' verdadeira.
     *
     * @return bool
     */
    private function userIsAdmin(): bool
    {
        $user = Auth::user();

        return $user !== null && $user->is_admin === true;
    }

    /**
     * Retorna resposta de acesso negado de forma apropriada,
     * respeitando o tipo da requisição (JSON ou web).
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    private function denyAccess(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => self::ACCESS_DENIED_MESSAGE], 403);
        }

        abort(403, self::ACCESS_DENIED_MESSAGE);
    }
}
