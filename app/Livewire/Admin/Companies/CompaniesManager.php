<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithPagination;

class CompaniesManager extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    // Propriedades para controle de navegação
    public $showForm = false;
    public $editingCompanyId = null;

    // Propriedades para modal de exclusão
    public $companyToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    protected $listeners = [
        'companySaved' => 'handleCompanySaved',
        'companyUpdated' => 'handleCompanyUpdated',
        'hideForm' => 'hideForm'
    ];

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
        $this->editingCompanyId = null;
    }

    public function showEditForm($companyId)
    {
        $this->showForm = true;
        $this->editingCompanyId = $companyId;
    }

    public function hideForm()
    {
        $this->showForm = false;
        $this->editingCompanyId = null;
    }

    public function showDeleteModal($companyId, $companyName)
    {
        $this->companyToDelete = [
            'id' => $companyId,
            'name' => $companyName
        ];
        $this->dispatch('show-delete-modal', companyName: $companyName);
    }

    public function confirmDelete()
    {
        if ($this->companyToDelete) {
            try {
                $company = Company::findOrFail($this->companyToDelete['id']);
                $company->delete();

                $this->dispatch('showAlert', message: 'Empresa excluída com sucesso!', type: 'success');
                $this->dispatch('hide-delete-modal');
            } catch (\Exception $e) {
                $this->dispatch('showAlert', message: 'Erro ao excluir empresa: ' . $e->getMessage(), type: 'error');
            }
        }

        $this->companyToDelete = null;
    }

    public function cancelDelete()
    {
        $this->companyToDelete = null;
        $this->dispatch('hide-delete-modal');
    }

    public function handleCompanySaved()
    {
        $this->hideForm();
        $this->dispatch('showAlert', message: 'Empresa criada com sucesso!', type: 'success');
    }

    public function handleCompanyUpdated()
    {
        $this->hideForm();
        $this->dispatch('showAlert', message: 'Empresa atualizada com sucesso!', type: 'success');
    }

    public function render()
    {
        $companies = Company::query()
            ->withCount('users')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('document', 'like', '%' . $this->search . '%')
                    ->orWhere('contact_email', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.companies.companies-manager', [
            'companies' => $companies
        ]);
    }
}
