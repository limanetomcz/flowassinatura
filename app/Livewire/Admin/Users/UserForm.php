<?php

namespace App\Livewire\Admin\Users;

use App\Http\Requests\UserFormRequest;
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
    protected UserFormRequest $formRequest;

    public function booted(UserService $userService, UserFormRequest $formRequest)
    {
        $this->userService = $userService;
        $this->formRequest = $formRequest;
    }

    protected $listeners = [
        'userSaved' => 'handleUserSaved',
        'userUpdated' => 'handleUserUpdated'
    ];

    public function getRules()
    {
        // Se estiver editando, simular que é uma requisição PUT
        if ($this->isEditing && $this->user) {
            $this->formRequest->merge(['_method' => 'PUT']);
            $this->formRequest->setRouteResolver(function () {
                return (object) ['parameter' => ['user' => $this->user->id]];
            });
        }

        return $this->formRequest->rules();
    }

    public function getMessages()
    {
        return $this->formRequest->messages();
    }

    public function mount($userId = null)
    {
        if ($userId) {
            $this->user = $this->userService->findOrFail($userId);
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->is_admin = $this->user->is_admin;
            $this->company_id = $this->user->company_id;
            $this->isEditing = true;
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'is_admin' => $this->is_admin,
            'company_id' => $this->company_id ?: null,
        ];

        // Adiciona senha apenas se fornecida
        if (!empty($this->password)) {
            $data['password'] = $this->password;
            $data['password_confirmation'] = $this->password_confirmation;
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
