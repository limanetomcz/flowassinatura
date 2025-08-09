<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Middleware\IsAdmin;

// Redireciona para 'home' se estiver autenticado, senão para 'login'
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

// Rotas de autenticação, com registro desabilitado
Auth::routes(['register' => false]);

// Rotas para usuários autenticados comuns
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

// Rotas para administradores autenticados e com middleware IsAdmin (FQCN)
Route::middleware(['auth', IsAdmin::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    // Outras rotas administrativas podem ser adicionadas aqui
});

// Fallback para rotas inválidas redirecionar para login (com checagem Auth)
Route::fallback(function () {
    return Auth::check() ? redirect()->route('home') : redirect()->route('login');
});
