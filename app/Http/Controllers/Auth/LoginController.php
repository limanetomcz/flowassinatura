<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * LoginController constructor.
     * Applies middleware to guests and authenticated users.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override the login method to use LoginRequest for validation.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        // LoginRequest automatically validates the input
        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Conditional redirect after login.
     *
     * @return string
     */
    protected function redirectTo(): string
    {
        $user = Auth::user();

        // If the user is not admin and tries to access an admin route, deny access
        if (!$user || (!$user->is_admin && request()->is('admin/*'))) {
            abort(403, 'Access denied. You do not have permission to access this area.');
        }

        // Redirect admins to the admin dashboard, others to /home
        return $user->is_admin ? '/admin/dashboard' : '/home';
    }

        /**
     * Resposta personalizada quando as credenciais são inválidas.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Retorna para a tela de login com mensagem amigável
        return back()
            ->withInput($request->only($this->username()))
            ->with('status', 'Credenciais inválidas.');
    }
}
