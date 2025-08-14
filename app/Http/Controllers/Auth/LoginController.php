<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/**
 * Controlador responsável pelo processo de autenticação (login) de usuários.
 *
 * Utiliza o trait AuthenticatesUsers do Laravel, que fornece a maior parte da
 * lógica padrão para autenticação, como validação de credenciais e redirecionamento.
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Cria uma nova instância do controlador.
     *
     * Define os middlewares aplicáveis:
     * - 'guest': impede usuários autenticados de acessar o formulário de login.
     * - 'auth': exige autenticação apenas para o logout.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Define para onde o usuário será redirecionado após o login.
     *
     * Essa lógica é executada automaticamente após o login bem-sucedido,
     * e determina o destino com base na role do usuário.
     *
     * @return string Caminho de redirecionamento apropriado.
     */
    protected function redirectTo(): string
    {
        $user = Auth::user();

        // 🔐 Segurança adicional: impede redirecionamento indevido para /admin caso o usuário não seja administrador
        if (!$user || !$user->is_admin && request()->is('admin/*')) {
            // Retorna erro 403 se o usuário tentou acessar rota administrativa sem permissão
            abort(403, 'Acesso negado. Você não tem permissão para acessar esta área.');
        }

        // 🔁 Redireciona de acordo com o perfil do usuário
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
