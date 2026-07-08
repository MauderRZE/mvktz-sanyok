<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\SoftwareLicense;
use App\Models\Asset;
use App\Livewire\Forms\SoftwareLicenseForm;

#[Layout('layouts.admin')]
class SoftwareLicenseManager extends Component
{
    public SoftwareLicenseForm $form;
    
    public $licenses;
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
            $isUpdate ? 'Ліцензію оновлено.' : 'Ліцензію додано.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $license = SoftwareLicense::findOrFail($id);
        $this->form->setLicense($license);
        $this->openModal();
    }

    public function delete($id)
    {
        SoftwareLicense::find($id)->delete();
        session()->flash('message', 'Ліцензію видалено.');
    }
}
