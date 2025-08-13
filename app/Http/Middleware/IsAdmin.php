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
     * Intercepta a requisição e verifica se o usuário é admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $user = Auth::user();

        // Garante boolean (1/true) e que esteja autenticado
        if (!$user || !(bool) $user->is_admin) {
            return $this->denyAccess($request);
        }

        return $next($request);
    }

    /**
     * Resposta de acesso negado de forma apropriada (JSON ou web).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    private function denyAccess(Request $request): mixed
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => self::ACCESS_DENIED_MESSAGE], 403);
        }

        abort(403, self::ACCESS_DENIED_MESSAGE);
    }
}
