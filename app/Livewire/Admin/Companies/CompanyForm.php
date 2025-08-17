<?php

namespace App\Livewire\Admin\Companies;

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

    protected $rules = [
        'name' => 'required|string|max:255',
        'document' => 'required|string|max:255',
        'contact_email' => 'required|email|max:255',
        'contact_number' => 'required|string|max:255',
    ];

    protected $messages = [
        'name.required' => 'O nome da empresa é obrigatório.',
        'name.max' => 'O nome da empresa não pode ter mais de 255 caracteres.',
        'document.required' => 'O documento é obrigatório.',
        'document.max' => 'O documento não pode ter mais de 255 caracteres.',
        'contact_email.required' => 'O email de contato é obrigatório.',
        'contact_email.email' => 'Digite um email válido.',
        'contact_email.max' => 'O email não pode ter mais de 255 caracteres.',
        'contact_number.required' => 'O número de contato é obrigatório.',
        'contact_number.max' => 'O número de contato não pode ter mais de 255 caracteres.',
    ];

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
