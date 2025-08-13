<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\AdminDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
| These routes are loaded by the RouteServiceProvider within a group
| which contains the "web" middleware group. Enjoy building your app!
|
*/

// Initial redirect: if authenticated, go to home, otherwise go to login
Route::get('/', function () {
    return Auth::check() ? redirect()->route('home') : redirect()->route('login');
});

// Authentication routes provided by Laravel UI
Auth::routes(['register' => false]);

// Override default login POST route to use LoginRequest for validation
Route::post('login', [LoginController::class, 'login'])->name('login');

// Routes accessible to any authenticated user
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

// Admin routes: only accessible by authenticated users with 'is_admin' middleware
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
});

// Fallback route: redirect to home if authenticated, or login if guest
Route::fallback(function () {
    return Auth::check() ? redirect()->route('home') : redirect()->route('login');
});
