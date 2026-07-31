<?php

namespace App\Http\Livewire\Admin;

use App\Livewire\Forms\SupplierForm;
use App\Models\Supplier;
use App\Models\SupplierType;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class SupplierManager extends Component
{
    public SupplierForm $form;

    public $suppliers;

    public $isOpen = 0;

    public $search = '';

    public $filterType = [];

    public function render()
    {
        $query = Supplier::with('supplierType')
            ->when($this->search, function ($q) {
                $search = '%'.$this->search.'%';
                $q->where(function ($sub) use ($search) {
                    $sub->where('supplier_name', 'like', $search)
                        ->orWhere('tax_code', 'like', $search);
                });
            })
            ->when(! empty($this->filterType), function ($q) {
                $hasNull = in_array('null', $this->filterType, true) || in_array(null, $this->filterType, true);
                $types = array_filter($this->filterType, fn ($v) => $v !== 'null' && $v !== null && $v !== '');
                $q->where(function ($sub) use ($types, $hasNull) {
                    if (! empty($types)) {
                        $sub->whereIn('supplier_type_id', $types);
                    }
                    if ($hasNull) {
                        $sub->orWhereNull('supplier_type_id');
                    }
                });
            })
            ->orderBy('id', 'desc');

        $this->suppliers = $query->get();

        return view('livewire.admin.supplier-manager', [
            'supplierTypes' => SupplierType::all(),
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
            $isUpdate ? 'Постачальника оновлено.' : 'Постачальника створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $sup = Supplier::findOrFail($id);
        $this->form->setSupplier($sup);
        $this->openModal();
    }

    public function delete($id)
    {
        Supplier::find($id)->delete();
        session()->flash('message', 'Постачальника видалено.');
    }
}
