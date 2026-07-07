<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\SoftwareLicense;
use App\Models\Asset;

#[Layout('layouts.admin')]
class SoftwareLicenseManager extends Component
{
    public $licenses, $licenseId, $vendor_id, $license_name, $license_type, $custom_license_type, $purchase_date;
    public $vendorsList = [];
    public $isOpen = 0;

    public $search = '';
    public $filterType = [];
    public $filterVendor = [];

    public function render()
    {
        $query = SoftwareLicense::with('vendor')
            ->when($this->search, function($q) {
                $search = '%' . $this->search . '%';
                $q->where(function($sub) use ($search) {
                    $sub->where('license_name', 'like', $search)
                        ->orWhereHas('vendor', function($v) use ($search) {
                            $v->where('supplier_name', 'like', $search);
                        });
                });
            })
            ->when(!empty($this->filterType), function($q) {
                $q->whereIn('license_type', $this->filterType);
            })
            ->when(!empty($this->filterVendor), function($q) {
                $q->whereIn('vendor_id', $this->filterVendor);
            })
            ->orderBy('id', 'desc');

        $this->licenses = $query->get();
        $this->vendorsList = \App\Models\Supplier::all();
        return view('livewire.admin.software-license-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterType = [];
        $this->filterVendor = [];
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
        $this->custom_license_type = '';
        $this->purchase_date = '';
    }

    public function store()
    {
        $this->validate([
            'vendor_id' => 'nullable|integer',
            'license_name' => 'required|string|max:255',
            'license_type' => 'nullable|string|max:100',
            'custom_license_type' => 'nullable|string|max:100',
            'purchase_date' => 'nullable|date',
        ]);

        $type = $this->license_type;
        if ($type === 'Інше') {
            $type = $this->custom_license_type;
        }

        SoftwareLicense::updateOrCreate(['id' => $this->licenseId], [
            'vendor_id' => $this->vendor_id ?: null,
            'license_name' => $this->license_name,
            'license_type' => $type ?: null,
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
        
        $predefined = ['OEM', 'Retail', 'Корпоративна', 'Підписка', 'Безкоштовна'];
        if (in_array($license->license_type, $predefined) || empty($license->license_type)) {
            $this->license_type = $license->license_type;
            $this->custom_license_type = '';
        } else {
            $this->license_type = 'Інше';
            $this->custom_license_type = $license->license_type;
        }
        
        $this->purchase_date = $license->purchase_date;
        $this->openModal();
    }

    public function delete($id)
    {
        SoftwareLicense::find($id)->delete();
        session()->flash('message', 'Ліцензію видалено.');
    }
}
