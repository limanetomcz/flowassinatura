<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Classe Kernel responsável por registrar e gerenciar os middlewares da aplicação.
 *
 * Aqui definimos:
 * - Middlewares globais que são aplicados a todas as requisições.
 * - Grupos de middlewares para rotas específicas (web, api).
 * - Middlewares que podem ser aplicados individualmente às rotas via aliases.
 */
class Kernel extends HttpKernel
{
    /**
     * Pilha global de middlewares HTTP.
     *
     * São middlewares executados em TODAS as requisições da aplicação,
     * independente da rota.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // Adicione middlewares globais aqui, por exemplo:
        // \App\Http\Middleware\TrustProxies::class,
        // \Fruitcake\Cors\HandleCors::class,
        // \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        // \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        // \App\Http\Middleware\TrimStrings::class,
        // \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * Grupos de middlewares para rotas específicas.
     *
     * Facilita aplicar múltiplos middlewares de uma vez em rotas web, api, etc.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // Middlewares padrão para rotas web, cuidando de sessões, cookies, CSRF, etc.
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class, // opcional
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // Middlewares padrão para rotas API, incluindo throttle e bindings
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * Middlewares individuais com alias para uso em rotas.
     *
     * Você pode aplicar esses middlewares diretamente em rotas ou controllers,
     * usando o nome chave (ex: 'auth', 'is_admin').
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // Registro do middleware personalizado para verificar administrador
        'is_admin' => \App\Http\Middleware\IsAdmin::class,
    ];
}

<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    // Middlewares globais aplicados a todas as requisições
    protected $middleware = [
        // Se quiser pode adicionar middlewares globais aqui
    ];

    // Grupos de middleware para rotas específicas (web, api)
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    // Middleware individuais (alias) para uso direto em rotas/controllers
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'is_admin' => \App\Http\Middleware\IsAdmin::class,
    ];
}
