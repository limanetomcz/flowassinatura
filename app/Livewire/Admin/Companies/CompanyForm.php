<?php

namespace App\Livewire\Admin\Companies;

use App\Http\Requests\CompanyFormRequest;
use App\Models\Company;
use Livewire\Component;

class CompanyForm extends Component
{
    public $company;
    public $name = '';
    public $document = '';
    public $contact_email = '';
    public $contact_number = '';
    public $isEditing = false;

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
            $this->company->update([
                'name' => $this->name,
                'document' => $this->document,
                'contact_email' => $this->contact_email,
                'contact_number' => $this->contact_number,
            ]);

            session()->flash('message', 'Empresa atualizada com sucesso!');
        } else {
            Company::create([
                'name' => $this->name,
                'document' => $this->document,
                'contact_email' => $this->contact_email,
                'contact_number' => $this->contact_number,
            ]);

            session()->flash('message', 'Empresa criada com sucesso!');
        }

        $this->redirect(route('admin.companies.index'));
    }

    public function render()
    {
        return view('livewire.admin.companies.company-form');
    }
}
