<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
    }

    protected function redirectTo(): string
    {
        $user = Auth::user();

        if (!$user || (!$user->is_admin && request()->is('admin/*'))) {
            abort(403, 'Acesso negado. Você não tem permissão para acessar esta área.');
        }

        return $user->is_admin ? '/admin/dashboard' : '/home';
    }
}
