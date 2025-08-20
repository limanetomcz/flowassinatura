<?php

namespace App\Livewire\Admin\Companies;

use App\Http\Requests\CompanyFormRequest;
use App\Models\Company;
use App\Services\CompanyService;
use Livewire\Component;

class CompanyForm extends Component
{
    public $company;
    public $name = '';
    public $document = '';
    public $contact_email = '';
    public $contact_number = '';
    public $isEditing = false;

    protected CompanyService $companyService;

    public function boot(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    protected $listeners = [
        'companySaved' => 'handleCompanySaved',
        'companyUpdated' => 'handleCompanyUpdated'
    ];

    public function getRules()
    {
        return (new CompanyFormRequest())->rules();
    }

    public function getMessages()
    {
        return (new CompanyFormRequest())->messages();
    }

    public function mount($companyId = null)
    {
        if ($companyId) {
            $this->company = Company::findOrFail($companyId);
            $this->name = $this->company->name;
            $this->document = $this->company->document;
            $this->contact_email = $this->company->contact_email;
            $this->contact_number = $this->company->contact_number;
            $this->isEditing = true;
        }
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $this->companyService->update($this->company, [
                'name' => $this->name,
                'document' => $this->document,
                'contact_email' => $this->contact_email,
                'contact_number' => $this->contact_number,
            ]);

            $this->dispatch('companyUpdated');
        } else {
            $this->companyService->create([
                'name' => $this->name,
                'document' => $this->document,
                'contact_email' => $this->contact_email,
                'contact_number' => $this->contact_number,
            ]);

            $this->dispatch('companySaved');
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
        $this->document = '';
        $this->contact_email = '';
        $this->contact_number = '';
        $this->isEditing = false;
        $this->company = null;
    }

    public function render()
    {
        return view('livewire.admin.companies.company-form');
    }
}
