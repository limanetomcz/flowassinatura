<?php

namespace App\Livewire\Admin\Users;

use App\Http\Requests\UserFormRequest;
use App\Models\User;
use App\Models\Company;
use App\Services\UserService;
use Livewire\Component;

class UserForm extends Component
{
    public $user;
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $is_admin = false;
    public $company_id = '';
    public $isEditing = false;

    protected UserService $userService;

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    protected $listeners = [
        'userSaved' => 'handleUserSaved',
        'userUpdated' => 'handleUserUpdated'
    ];

    public function getRules()
    {
        return (new UserFormRequest())->rules();
    }

    public function getMessages()
    {
        return (new UserFormRequest())->messages();
    }

    public function mount($userId = null)
    {
        if ($userId) {
            $this->user = User::findOrFail($userId);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->is_admin = $this->user->is_admin;
            $this->company_id = $this->user->company_id;
            $this->isEditing = true;
        }
    }

    public function save()
    {
        // Validação customizada para senha
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email' . ($this->isEditing ? ',' . $this->user->id : ''),
            'is_admin' => 'boolean',
            'company_id' => 'nullable|exists:companies,id',
        ];

        // Adiciona validação de senha apenas se fornecida ou se for criação
        if (!empty($this->password) || !$this->isEditing) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'company_id' => $this->company_id ?: null,
        ];

        // Adiciona senha apenas se fornecida
        if (!empty($this->password)) {
            $data['password'] = $this->password;
        }

        if ($this->isEditing) {
            $this->userService->update($this->user, $data);
            $this->dispatch('userUpdated');
        } else {
            $this->userService->create($data);
            $this->dispatch('userSaved');
        }

        // Limpar formulário
        $this->resetForm();
    }

    public function cancel()
    {
        $this->dispatch('hideForm');
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->is_admin = false;
        $this->company_id = '';
        $this->isEditing = false;
        $this->user = null;
    }

    public function render()
    {
        $companies = Company::orderBy('name')->get();

        return view('livewire.admin.users.user-form', [
            'companies' => $companies
        ]);
    }
}
