<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

/**
 * Classe Kernel responsável por registrar e gerenciar middlewares da aplicação.
 *
 * Essa classe configura quais middlewares serão aplicados globalmente,
 * em grupos específicos (ex: web, api) e quais estarão disponíveis
 * via alias para uso direto em rotas ou controllers.
 *
 * @package App\Http
 */
class Kernel extends HttpKernel
{
    /**
     * Middlewares globais (aplicados a TODAS as requisições HTTP).
     *
     * Esses middlewares atuam no pipeline de toda requisição, garantindo
     * segurança, manipulação de headers, tratamento de sessões e outras funções
     * que devem ser sempre executadas.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // Exemplo de middlewares globais importantes:
        // \App\Http\Middleware\TrustProxies::class,          // Configura proxies confiáveis (ex: Cloudflare, Nginx)
        // \Fruitcake\Cors\HandleCors::class,                 // Habilita suporte a CORS para comunicação cross-origin
        // \App\Http\Middleware\PreventRequestsDuringMaintenance::class, // Bloqueia requisições em modo manutenção
        // \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class, // Limita tamanho do corpo da requisição
        // \App\Http\Middleware\TrimStrings::class,            // Remove espaços em branco de strings de entrada
        // \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class, // Converte strings vazias para null
    ];

    /**
     * Grupos de middlewares para rotas específicas.
     *
     * Facilita aplicar múltiplos middlewares relacionados de uma vez
     * em rotas que compartilham características (ex: rotas web e API).
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            // Middleware essencial para rotas web com estado (sessão, CSRF, cookies)
            \App\Http\Middleware\EncryptCookies::class,               // Criptografa cookies
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // Adiciona cookies à resposta
            \Illuminate\Session\Middleware\StartSession::class,       // Inicializa sessão PHP
            // \Illuminate\Session\Middleware\AuthenticateSession::class, // Pode invalidar sessão após logout (opcional)
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // Disponibiliza erros de validação nas views
            \App\Http\Middleware\VerifyCsrfToken::class,              // Proteção contra CSRF
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // Resolve injeção de dependências em rotas
        ],

        'api' => [
            // Middlewares padrão para rotas API RESTful
            'throttle:api',                                             // Limita taxa de requisições (ex: 60/min)
            \Illuminate\Routing\Middleware\SubstituteBindings::class,  // Resolve bindings nas rotas API
        ],
    ];

    /**
     * Middlewares individuais (aliases) para uso direto em rotas ou controllers.
     *
     * Permitem aplicar regras específicas de forma simples, usando a chave do array.
     * Exemplo: ['middleware' => 'auth'] em rotas que requerem autenticação.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        // Autenticação e autorização
        'auth' => \App\Http\Middleware\Authenticate::class,               // Verifica se usuário está autenticado
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class, // Autenticação HTTP Basic
        'can' => \Illuminate\Auth\Middleware\Authorize::class,           // Verifica permissões via gates/policies
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,  // Redireciona usuários autenticados (ex: login)

        // Segurança e validações extras
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class, // Solicita senha recente
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,      // Valida URLs assinadas
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,     // Controla taxa de requisições
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,   // Exige e-mail verificado

        // Cache e Headers
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,   // Define cabeçalhos de cache

        // Middleware personalizado
        'is_admin' => \App\Http\Middleware\IsAdmin::class,                       // Verifica se usuário é administrador
    ];
}
