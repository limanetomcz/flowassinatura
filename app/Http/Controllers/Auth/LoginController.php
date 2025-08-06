<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Define para onde redirecionar após login com base na role do usuário.
     *
     * @return string
     */
    protected function redirectTo(): string
    {
        $user = Auth::user();

        return $user->is_admin ? '/admin/dashboard' : '/home';
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
