<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * ============================================================================
 * Login Controller
 * ============================================================================
 *
 * Handles user authentication (login/logout).
 * - Uses Laravel's AuthenticatesUsers trait for most of the login logic.
 * - Custom redirect after login (admin vs regular user).
 * - Custom error messages on login failure.
 * ============================================================================
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Constructor
     * Applies middlewares:
     * - guest: blocks authenticated users from accessing login form.
     * - auth: only allows authenticated users to logout.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Redirect path after login based on role.
     *
     * @return string
     */
    protected function redirectTo(): string
    {
        $user = Auth::user();

        return $user && $user->is_admin
            ? route('admin.dashboard')
            : route('home');
    }

    /**
     * Custom failed login response.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return back()
            ->withInput($request->only($this->username()))
            ->withErrors([
                $this->username() => 'Credenciais inválidas. Verifique seu email e senha.',
            ]);
    }
}
