<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Organization;
use App\Livewire\Forms\OrganizationForm;

#[Layout('layouts.admin')]
class OrganizationManager extends Component
{
    public OrganizationForm $form;
    
    public $organizations;
    public $isOpen = 0;

    public $search = '';
    public $filterType = [];

    public function render()
    {
        $query = Organization::query()
            ->when($this->search, function($q) {
                $search = '%' . $this->search . '%';
                $q->where(function($sub) use ($search) {
                    $sub->where('org_name', 'like', $search)
                        ->orWhere('org_type', 'like', $search);
                });
            })
            ->when(!empty($this->filterType), function($q) {
                $q->whereIn('org_type', $this->filterType);
            })
            ->orderBy('id', 'desc');

        $this->organizations = $query->get();
        
        $typesList = Organization::select('org_type')->distinct()->pluck('org_type')->filter()->values();

        return view('livewire.admin.organization-manager', [
            'typesList' => $typesList
        ]);
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterType = [];
    }

    public function create()
    {
        $this->form->reset();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function store()
    {
        $isUpdate = $this->form->store();

        session()->flash('message', 
            $isUpdate ? 'Організацію оновлено.' : 'Організацію створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $org = Organization::findOrFail($id);
        $this->form->setOrganization($org);
        $this->openModal();
    }

    public function delete($id)
    {
        Organization::find($id)->delete();
        session()->flash('message', 'Організацію видалено.');
    }
}
