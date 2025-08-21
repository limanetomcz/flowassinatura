<?php

namespace App\Livewire\Admin\Companies;

use App\Models\Company;
use App\Services\CompanyService;
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

    protected CompanyService $companyService;

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

    public function boot(CompanyService $companyService)
    {
        $this->companyService = $companyService;
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
                $company = $this->companyService->findOrFail($this->companyToDelete['id']);
                $this->companyService->delete($company);

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
        $companies = $this->search 
            ? $this->companyService->search($this->search, $this->perPage)
            : $this->companyService->getWithUsers($this->perPage);

        return view('livewire.admin.companies.companies-manager', [
            'companies' => $companies
        ]);
    }
}
