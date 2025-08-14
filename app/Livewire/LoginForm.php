<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoginForm extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $error = '';

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    protected $messages = [
        'email.required' => 'O campo email é obrigatório.',
        'email.email' => 'Digite um email válido.',
        'password.required' => 'O campo senha é obrigatório.',
        'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->error = ''; // Limpa erro quando usuário digita
    }

    public function login()
    {
        $this->validate();

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            session()->flash('message', 'Login realizado com sucesso!');
            
            // Verifica se o usuário é administrador
            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/home');
            }
        } else {
            $this->error = 'Credenciais inválidas. Verifique seu email e senha.';
        }
    }

    public function render()
    {
        return view('livewire.login-form');
    }
}
