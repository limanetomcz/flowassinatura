<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use App\Services\UserService;
use Livewire\Component;
use Livewire\WithPagination;

class UsersManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Propriedades para controle de navegação
    public $showForm = false;
    public $editingUserId = null;

    // Propriedades para modal de exclusão
    public $userToDelete = null;

    protected UserService $userService;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $listeners = [
        'userSaved' => 'handleUserSaved',
        'userUpdated' => 'handleUserUpdated',
        'hideForm' => 'hideForm'
    ];

    public function boot(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function showCreateForm()
    {
        $this->showForm = true;
        $this->editingUserId = null;
    }

    public function showEditForm($userId)
    {
        $this->showForm = true;
        $this->editingUserId = $userId;
    }

    public function hideForm()
    {
        $this->showForm = false;
        $this->editingUserId = null;
    }

    public function showDeleteModal($userId, $userName)
    {
        $this->userToDelete = [
            'id' => $userId,
            'name' => $userName
        ];
        $this->dispatch('show-delete-modal', companyName: $userName);
    }

    public function confirmDelete()
    {
        if ($this->userToDelete) {
            try {
                $user = $this->userService->findOrFail($this->userToDelete['id']);
                $this->userService->delete($user);

                $this->dispatch('showAlert', message: 'Usuário excluído com sucesso!', type: 'success');
                $this->dispatch('hide-delete-modal');
            } catch (\Exception $e) {
                $this->dispatch('showAlert', message: 'Erro ao excluir usuário: ' . $e->getMessage(), type: 'error');
            }
        }

        $this->userToDelete = null;
    }

    public function handleUserSaved()
    {
        $this->hideForm();
        $this->dispatch('showAlert', message: 'Usuário criado com sucesso!', type: 'success');
    }

    public function handleUserUpdated()
    {
        $this->hideForm();
        $this->dispatch('showAlert', message: 'Usuário atualizado com sucesso!', type: 'success');
    }

    public function render()
    {
        $users = $this->search
            ? $this->userService->search($this->search, $this->perPage)
            : $this->userService->getWithCompany($this->perPage);

        return view('livewire.admin.users.users-manager', [
            'users' => $users
        ]);
    }
}
