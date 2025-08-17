<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Controller responsible for handling user authentication (login).
 *
 * Uses Laravel's AuthenticatesUsers trait, which provides most of the
 * default authentication logic, such as credential validation and redirection.
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * Sets up the applicable middleware:
     * - 'guest': prevents authenticated users from accessing the login form.
     * - 'auth': requires authentication only for logout.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Determines where the user should be redirected after login.
     *
     * This logic is executed automatically after a successful login,
     * and determines the destination based on the user's role.
     *
     * @return string Appropriate redirect path.
     */
    protected function redirectTo(): string
    {
        $user = Auth::user();

        // Additional security: prevent redirect to /admin if the user is not an admin
        if (!$user || (!$user->is_admin && request()->is('admin/*'))) {
            // Return 403 if the user attempted to access an admin route without permission
            abort(403, 'Access denied. You do not have permission to access this area.');
        }

        // Redirect based on user profile
        return $user->is_admin ? '/admin/dashboard' : '/home';
    }

    /**
     * Custom response when login credentials are invalid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Redirect back to the login page with a friendly error message
        return back()
            ->withInput($request->only($this->username()))
            ->with('status', 'Invalid credentials.');
    }
}
