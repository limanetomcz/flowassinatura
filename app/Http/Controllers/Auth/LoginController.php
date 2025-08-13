<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * LoginController constructor.
     * Applies middleware to restrict access to guests and authenticated users.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Validate the login request using LoginRequest.
     *
     * @param LoginRequest $request
     */
    protected function validateLogin(LoginRequest $request)
    {
        // Executes the validation defined in the Form Request
        $request->validated();
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

        // Redirect admins to admin dashboard, others to home
        return $user->is_admin ? '/admin/dashboard' : '/home';
    }
}
