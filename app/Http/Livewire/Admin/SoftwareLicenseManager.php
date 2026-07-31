<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\SoftwareLicenseForm;
use App\Models\SoftwareLicense;
use App\Models\Supplier;
use Livewire\Attributes\Layout;
use Livewire\Component;

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
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('license_name', 'like', $search)
                        ->orWhereHas('vendor', function ($v) use ($search) {
                            $v->where('supplier_name', 'like', $search);
                        });
                });
            })
            ->when(! empty($this->filterType), function ($q) {
                $hasNull = in_array('null', $this->filterType, true) || in_array(null, $this->filterType, true);
                $types = array_filter($this->filterType, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($types, $hasNull) {
                    if (! empty($types)) {
                        $sub->whereIn('license_type', $types);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('license_type');
                    }
                });
            })
            ->when(! empty($this->filterVendor), function ($q) {
                $hasNull = in_array('null', $this->filterVendor, true) || in_array(null, $this->filterVendor, true);
                $vendors = array_filter($this->filterVendor, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($vendors, $hasNull) {
                    if (! empty($vendors)) {
                        $sub->whereIn('vendor_id', $vendors);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('vendor_id');
                    }
                });
            })
            ->orderBy('id', 'desc');

        $this->licenses = $query->get();
        $this->vendorsList = Supplier::all();

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
