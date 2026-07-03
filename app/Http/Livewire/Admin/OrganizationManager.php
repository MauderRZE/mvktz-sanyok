<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Organization;

#[Layout('layouts.admin')]
class OrganizationManager extends Component
{
    public $organizations, $orgId, $org_name, $org_type = 'Стороння';
    public $isOpen = 0;

    public function render()
    {
        $this->organizations = Organization::all();
        return view('livewire.admin.organization-manager');
    }

    public function create()
    {
        $this->resetInputFields();
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

    private function resetInputFields(){
        $this->orgId = null;
        $this->org_name = '';
        $this->org_type = 'Стороння';
    }

    public function store()
    {
        $this->validate([
            'org_name' => 'required|unique:organizations,org_name,' . $this->orgId,
            'org_type' => 'required|string|max:100',
        ]);

        Organization::updateOrCreate(['id' => $this->orgId], [
            'org_name' => $this->org_name,
            'org_type' => $this->org_type
        ]);

        session()->flash('message', 
            $this->orgId ? 'Організацію оновлено.' : 'Організацію створено.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $org = Organization::findOrFail($id);
        $this->orgId = $id;
        $this->org_name = $org->org_name;
        $this->org_type = $org->org_type;
        $this->openModal();
    }

    public function delete($id)
    {
        Organization::find($id)->delete();
        session()->flash('message', 'Організацію видалено.');
    }
}
