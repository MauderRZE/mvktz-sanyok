<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Contract;
use App\Models\Supplier;
use App\Livewire\Forms\ContractForm;

#[Layout('layouts.admin')]
class ContractManager extends Component
{
    public ContractForm $form;
    
    public $contracts;
    public $suppliersList = [];
    public $isOpen = 0;

    public $search = '';
    public $filterSupplier = [];

    public function render()
    {
        $query = Contract::with('supplier')
            ->when($this->search, function($q) {
                $search = '%' . $this->search . '%';
                $q->where(function($sub) use ($search) {
                    $sub->where('contract_number', 'like', $search)
                        ->orWhereHas('supplier', function($sup) use ($search) {
                            $sup->where('supplier_name', 'like', $search);
                        });
                });
            })
            ->when(!empty($this->filterSupplier), function($q) {
                $q->whereIn('supplier_id', $this->filterSupplier);
            })
            ->orderBy('id', 'desc');

        $this->contracts = $query->get();
        $this->suppliersList = Supplier::all();
        return view('livewire.admin.contract-manager');
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterSupplier = [];
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
            $isUpdate ? 'Договір оновлено.' : 'Договір створено.');

        $this->closeModal();
    }

    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        $this->form->setContract($contract);
        $this->openModal();
    }

    public function delete($id)
    {
        Contract::find($id)->delete();
        session()->flash('message', 'Договір видалено.');
    }
}
