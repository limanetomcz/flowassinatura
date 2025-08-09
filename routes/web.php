<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminDashboardController;

// Redireciona para 'home' se estiver autenticado, senão para 'login'
Route::get('/', function () {
    return redirect()->route(Auth::check() ? 'home' : 'login');
});

// Rotas de autenticação, com registro desabilitado
Auth::routes(['register' => false]);

// Rotas para usuários autenticados comuns
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

// Rotas para administradores autenticados e com middleware is_admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    // Outras rotas administrativas podem ser adicionadas aqui
});

// Fallback para rotas inválidas redirecionar para login
Route::fallback(function () {
    return redirect()->route('login');
});
