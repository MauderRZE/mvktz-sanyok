<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\SoftwareLicense;
use App\Models\EquipmentComponent;

#[Layout('layouts.admin')]
class SoftwareLicenseManager extends Component
{
    public $licenses, $licenseId, $vendor_id, $license_name, $license_type, $purchase_date;
    public $vendorsList = [];
    public $isOpen = 0;

    public function render()
    {
        $this->licenses = SoftwareLicense::all();
        // Since we don't know if there's a vendors model, we'll just leave it empty or fetch from somewhere. 
        // For now, let's just make it empty if we don't have a specific Vendor model.
        // Or if there is a Vendor model, let's try to load it. For now, empty array.
        $this->vendorsList = []; 
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
        $this->vendor_id = null;
        $this->license_name = '';
        $this->license_type = '';
        $this->purchase_date = '';
    }

    public function store()
    {
        $this->validate([
            'vendor_id' => 'nullable|integer',
            'license_name' => 'required|string|max:255',
            'license_type' => 'nullable|string|max:100',
            'purchase_date' => 'nullable|date',
        ]);

        SoftwareLicense::updateOrCreate(['id' => $this->licenseId], [
            'vendor_id' => $this->vendor_id ?: null,
            'license_name' => $this->license_name,
            'license_type' => $this->license_type ?: null,
            'purchase_date' => $this->purchase_date ?: null,
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
        $this->vendor_id = $license->vendor_id;
        $this->license_name = $license->license_name;
        $this->license_type = $license->license_type;
        $this->purchase_date = $license->purchase_date;
        $this->openModal();
    }

    public function delete($id)
    {
        SoftwareLicense::find($id)->delete();
        session()->flash('message', 'Ліцензію видалено.');
    }
}
