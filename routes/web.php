<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\IsAdmin;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    }
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

// Rotas para administradores autenticados e com middleware is_admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('companies', CompanyController::class)->names([
        'index' => 'admin.companies.index',
        'create' => 'admin.companies.create',
        'edit' => 'admin.companies.edit',
    ]);
    Route::resource('users', UserController::class)->names([
        'index' => 'admin.users.index',
        'create' => 'admin.users.create',
        'edit' => 'admin.users.edit',
    ]);
});

// Fallback para rotas inválidas redirecionar para login
Route::fallback(function () {
    return Auth::check() ? redirect()->route('home') : redirect()->route('login');
});
