<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoginForm extends Component
{
    // Public properties bound to the form inputs
    public $email = '';
    public $password = '';
    public $remember = false;
    public $error = '';

    // Validation rules for email and password
    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    // Custom validation messages 
    protected $messages = [
        'email.required' => 'O campo email é obrigatório.',
        'email.email' => 'Digite um email válido.',
        'password.required' => 'O campo senha é obrigatório.',
        'password.min' => 'A senha deve ter pelo menos 6 caracteres.',
    ];

    /**
     * Called whenever a property is updated.
     * Validates only the updated property and clears general error messages.
     *
     * @param string $propertyName
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
        $this->error = ''; // Limpa erro quando o usuário digita
    }

    /**
     * Attempt to log in the user with the provided credentials.
     * Redirects based on user role or sets an error message if login fails.
     */
    public function login()
    {
        $this->validate(); // Validate all fields

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            session()->flash('message', 'Login realizado com sucesso!');

            // Redirect to admin dashboard if user is admin, otherwise to home
            if (Auth::user()->is_admin) {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/home');
            }
        } else {
            // Set a general error message (in Portuguese)
            $this->error = 'Credenciais inválidas. Verifique seu email e senha.';
        }
    }

    /**
     * Render the Livewire component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.login-form');
    }
}
