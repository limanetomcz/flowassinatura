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

    protected $listeners = [
        'companySaved' => 'handleCompanySaved',
        'companyUpdated' => 'handleCompanyUpdated'
    ];

    public function boot(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function mount($companyId = null)
    {
        if ($companyId) {
            $this->company = $this->companyService->findOrFail($companyId);
            $this->name = $this->company->name;
            $this->document = $this->company->document;
            $this->contact_email = $this->company->contact_email;
            $this->contact_number = $this->company->contact_number;
            $this->isEditing = true;
        }
    }

    public function rules(): array
    {
        return CompanyFormRequest::baseRules($this->company?->id);
    }

    public function messages(): array
    {
        return CompanyFormRequest::baseMessages();
    }

    public function attributes(): array
    {
        return CompanyFormRequest::baseAttributes();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'document' => $this->document,
            'contact_email' => $this->contact_email,
            'contact_number' => $this->contact_number,
        ];

        if ($this->isEditing) {
            $this->companyService->update($this->company, $data);
            $this->dispatch('companyUpdated');
        } else {
            $this->companyService->create($data);
            $this->dispatch('companySaved');
        }

        $this->dispatch('hideForm');
    }

    public function cancel()
    {
        $this->dispatch('hideForm');
    }

    public function render()
    {
        return view('livewire.admin.companies.company-form');
    }
}
