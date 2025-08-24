<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoginForm extends Component
{
    // =========================================================================
    // Public Properties
    // =========================================================================
    public $email = '';
    public $password = '';
    public $remember = false;
    public $error = '';

    // =========================================================================
    // Validation Rules
    // =========================================================================
    protected $rules = [
        'email'    => 'required|email',
        'password' => 'required|min:6',
    ];

    // =========================================================================
    // Custom Validation Messages
    // =========================================================================
    protected $messages = [
        'email.required'    => 'O campo email é obrigatório.',
        'email.email'       => 'Digite um email válido.',
        'password.required' => 'O campo senha é obrigatório.',
        'password.min'      => 'A senha deve ter pelo menos 6 caracteres.',
    ];

    // =========================================================================
    // Live Validation: Triggered When A Property Is Updated
    // =========================================================================
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->error = ''; // Clear previous error when user types
    }

    // =========================================================================
    // Login Method: Authenticate User Directly
    // =========================================================================
    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();

            // Redirect user according to role
            return redirect()->intended(
                Auth::user()->is_admin ? '/admin/dashboard' : '/home'
            );
        }

        // Failed login
        $this->error = 'Credenciais inválidas. Verifique seu email e senha.';
    }

    // =========================================================================
    // Render The Component
    // =========================================================================
    public function render()
    {
        return view('livewire.login-form');
    }
}
