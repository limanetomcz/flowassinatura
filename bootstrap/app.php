<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Registra alias de middleware para uso nas rotas
        $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
        ]);

        // Você pode registrar outros aliases, grupos ou middlewares globais aqui
        // Exemplo para grupo:
        // $middleware->group('web', [
        //     \App\Http\Middleware\EncryptCookies::class,
        //     \Illuminate\Session\Middleware\StartSession::class,
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Configurações para tratamento de exceções, se precisar
    })
    ->create();
