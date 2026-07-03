<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\SoftwareLicense;
use App\Models\EquipmentComponent;

#[Layout('layouts.admin')]
class SoftwareLicenseManager extends Component
{
    public $licenses, $licenseId, $component_id, $software_name, $license_key, $license_status = 'Активна', $expiration_date;
    public $componentsList = [];
    public $isOpen = 0;

    public function render()
    {
        $this->licenses = SoftwareLicense::with('component.equipment', 'component.componentType')->get();
        $this->componentsList = EquipmentComponent::with('equipment', 'componentType')->get();
        return view('livewire.admin.software-license-manager');
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
        $this->licenseId = null;
        $this->component_id = null;
        $this->software_name = '';
        $this->license_key = '';
        $this->license_status = 'Активна';
        $this->expiration_date = '';
    }

    public function store()
    {
        $this->validate([
            'component_id' => 'required|exists:equipment_components,id',
            'software_name' => 'required|string|max:150',
            'license_key' => 'nullable|string|max:255',
            'license_status' => 'required|string|max:50',
            'expiration_date' => 'nullable|date',
        ]);

        SoftwareLicense::updateOrCreate(['id' => $this->licenseId], [
            'component_id' => $this->component_id,
            'software_name' => $this->software_name,
            'license_key' => $this->license_key ?: null,
            'license_status' => $this->license_status,
            'expiration_date' => $this->expiration_date ?: null,
        ]);

        session()->flash('message', 
            $this->licenseId ? 'Ліцензію оновлено.' : 'Ліцензію додано.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $license = SoftwareLicense::findOrFail($id);
        $this->licenseId = $id;
        $this->component_id = $license->component_id;
        $this->software_name = $license->software_name;
        $this->license_key = $license->license_key;
        $this->license_status = $license->license_status;
        $this->expiration_date = $license->expiration_date;
        $this->openModal();
    }

    public function delete($id)
    {
        SoftwareLicense::find($id)->delete();
        session()->flash('message', 'Ліцензію видалено.');
    }
}
