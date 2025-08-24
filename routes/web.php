<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Admin\DocumentsController;
use App\Http\Controllers\Admin\SignatureController;
use App\Http\Controllers\Auth\LoginController;

/**
 * =============================================================================
 * Public & Authentication Routes
 * =============================================================================
 *
 * Purpose:
 *   Routes accessible without authentication or used for login.
 */

/**
 * -------------------------------------------------------------------------
 * Livewire Login Endpoint
 * -------------------------------------------------------------------------
 * Used by the Livewire LoginForm component for authentication.
 * POST /livewire-login -> LoginController@livewireLogin
 */
Route::post('/livewire-login', [LoginController::class, 'livewireLogin'])->name('livewire.login');

/**
 * -------------------------------------------------------------------------
 * Public Redirect
 * -------------------------------------------------------------------------
 * Redirects users to /home if authenticated, otherwise to /login.
 */
Route::get('/', function () {
    return Auth::check() ? redirect()->route('home') : redirect()->route('login');
});

/**
 * -------------------------------------------------------------------------
 * Authentication Routes
 * -------------------------------------------------------------------------
 * Standard Laravel auth routes (registration disabled).
 */
Auth::routes(['register' => false]);

/**
 * -------------------------------------------------------------------------
 * Authenticated Non-Admin Routes
 * -------------------------------------------------------------------------
 * Routes accessible only to logged-in users without admin privileges.
 */
Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});

/**
 * =============================================================================
 * Admin Routes (auth + is_admin)
 * =============================================================================
 *
 * Purpose:
 *   Routes protected by authentication and admin middleware.
 *   Prefix: /admin
 *   Name Prefix: admin.*
 */
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {

    /**
     * -------------------------------------------------------------------------
     * Admin Dashboard
     * -------------------------------------------------------------------------
     */
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    /**
     * -------------------------------------------------------------------------
     * Companies CRUD (RESTful)
     * -------------------------------------------------------------------------
     */
    Route::resource('companies', CompanyController::class)->names([
        'index'   => 'companies.index',
        'create'  => 'companies.create',
        'store'   => 'companies.store',
        'show'    => 'companies.show',
        'edit'    => 'companies.edit',
        'update'  => 'companies.update',
        'destroy' => 'companies.destroy',
    ]);

    /**
     * -------------------------------------------------------------------------
     * Documents CRUD (RESTful)
     * -------------------------------------------------------------------------
     */
    Route::resource('documents', DocumentsController::class)->names([
        'index'   => 'documents.index',
        'create'  => 'documents.create',
        'store'   => 'documents.store',
        'show'    => 'documents.show',
        'edit'    => 'documents.edit',
        'update'  => 'documents.update',
        'destroy' => 'documents.destroy',
    ]);

    /**
     * -------------------------------------------------------------------------
     * Signatures Nested CRUD (Documents -> Signatures)
     * -------------------------------------------------------------------------
     * RESTful nested resource, no shallow.
     */
    Route::resource('documents.signatures', SignatureController::class)->names([
        'index'   => 'documents.signatures.index',
        'create'  => 'documents.signatures.create',
        'store'   => 'documents.signatures.store',
        'show'    => 'documents.signatures.show',
        'edit'    => 'documents.signatures.edit',
        'update'  => 'documents.signatures.update',
        'destroy' => 'documents.signatures.destroy',
    ]);

    /**
     * -------------------------------------------------------------------------
     * Notify Signature (Custom Action)
     * -------------------------------------------------------------------------
     * Purpose: Sends a notification for a specific signature of a document.
     * POST /admin/documents/{document}/signatures/{signature}/notify
     */
    Route::post('documents/{document}/signatures/{signature}/notify', [SignatureController::class, 'notify'])
        ->name('documents.signatures.notify');
});

/**
 * =============================================================================
 * Fallback Route
 * =============================================================================
 * Redirects to home or login depending on authentication status if no other
 * routes match.
 */
Route::fallback(function () {
    return Auth::check() ? redirect()->route('home') : redirect()->route('login');
});
